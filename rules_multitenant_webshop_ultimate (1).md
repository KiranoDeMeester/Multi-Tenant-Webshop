# rules.md — Multi-Tenant Webshop (Enterprise SaaS Version)

## Doel van dit document
Dit document definieert de volledige bouwstandaard voor een enterprise-grade multi-tenant SaaS webshop.

Dit project moet aantonen:
- Enterprise-level architectuur (Multi-DB)
- Fysieke data-isolatie
- Schaalbaarheid naar duizenden tenants
- Moderne SaaS payment flow (Stripe Connect)
- Flexibel Theme System (CSS Vars + Overrides)

Stack:
- Laravel 13
- Livewire 4
- MySQL/MariaDB (Landlord + Dynamic Tenant DBs)
- Pest
- Vite
- Stripe Connect

---

# 1. Projectvisie

Dit is een schaalbaar SaaS platform (Multi-Tenant).

Elke tenant:
- Heeft een eigen fysieke database (Physical Isolation)
- Heeft een eigen Stripe Connected Account
- Beheert eigen styling via een Dashboard (CSS Variables)

---

# 2. Niet-onderhandelbare regels

## Verboden
- business logic in Blade/Routes
- float voor geld (altijd decimal of integers/cents)
- directe DB calls in views
- hardcoded database names
- Stripe Secret Keys in de code (gebruik env/DB)

## Verplicht
- Multi-database architectuur
- Stripe Connect (Direct/Destination charges)
- CSS Variable injection voor theming
- Services + Actions pattern
- Typed code (PHP 8.3+)

---

# 3. Multi-Tenant Architectuur (Multi-DB)

## Strategie
**Physical Isolation**: 1 Landlord DB + N Tenant DBs.

### Landlord Database
Bevat:
- `tenants` (id, name, domain, db_name, db_user, db_password)
- `domains`
- `platform_settings`

### Tenant Database
Bevat alle webshop data:
- `products`, `orders`, `customers`, `settings`, etc.

---

# 4. Tenant Resolving & Connection Switching

Elke request wordt onderschept door een Middleware.

## Flow:
1. Detecteer host (e.g., `apple.localhost`)
2. Zoek tenant in **Landlord DB**
3. Switch de `tenant` connection settings dynamisch:
   ```php
   Config::set('database.connections.tenant.database', $tenant->db_name);
   DB::purge('tenant');
   DB::reconnect('tenant');
   ```
4. Stel de default connection in op `tenant` voor de rest van de request.

---

# 5. Migration Management

## Verplicht
Gebruik een command om migraties over alle tenants te runnen:
```bash
php artisan tenants:migrate
```
Dit loop door alle tenants in de Landlord DB en voert de migraties uit op de tenant-connection.

---

# 6. Theme System (Hybrid Injection)

## Strategie 1: CSS Variables (The "Skin")
De tenant beheert kleuren/fonts in hun dashboard. Deze worden geïnjecteerd in de layout:
```html
<style>
  :root {
    --primary: {{ $tenant->colors['primary'] }};
    --secondary: {{ $tenant->colors['secondary'] }};
  }
</style>
```

## Strategie 2: Layout Types
Tenants kiezen uit predefined layouts (e.g., 'minimal', 'sidebar', 'modern').
Code: `<x-dynamic-component :component="'layouts.' . $tenant->layout_type">`

## Strategie 3: View Overrides
Fallback mechanisme:
1. Zoek in `resources/views/themes/{tenant_slug}/...`
2. Fallback naar `resources/views/themes/default/...`

---

# 7. Stripe Connect Architectuur

## Setup
De platform-eigenaar heeft de hoofd-account.
Elke tenant moet een "Connect" flow doorlopen om betalingen te ontvangen.

## Verplicht
- Gebruik **Stripe Connect** (Destination Charges of Separate Charges).
- Sla `stripe_account_id` op in de `tenants` tabel (Landlord).
- Checkout Sessions moeten de `stripe_account` parameter bevatten.

---

# 8. Architectuur Lagen

## Actions
- `CreateTenantAction` (maakt DB, runt migraties, maakt user)
- `CreateOrderAction`
- `ProcessStripeWebhookAction`

## Services
- `StripeConnectService`
- `TenantDatabaseService` (raw SQL voor DB management)

---

# 9. Database Regels
- Geld: `decimal(10,2)` of `integer` (centen)
- Foreign keys verplicht binnen de tenant DB
- Geen cross-database joins (Landlord joins met Tenant DB zijn verboden)

---

# 10. Security
- Tenant data is fysiek gescheiden; een bug in een query kan nooit data van een andere klant lekken.
- Landlord data is alleen toegankelijk voor platform-admins.

---

# 11. Testing (Pest)
- Test of de connection switcher correct werkt.
- Test of `tenants:migrate` alle databases bijwerkt.
- Mock Stripe Connect flows.

---

# 12. Hoofdregel
Bouw dit als een schaalbaar SaaS platform voor duizenden webshops. Elke handmatige actie is een fout; alles moet geautomatiseerd zijn via Actions en Services.

