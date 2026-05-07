# rules.md — Multi-Tenant Webshop (Enterprise SaaS Version)

## Doel van dit document
Dit document definieert de volledige bouwstandaard voor een enterprise-grade multi-tenant SaaS webshop (Traject B).

Dit project moet aantonen:
- Enterprise-level architectuur (Multi-DB)
- Diepgang in logging, foutafhandeling en performantie
- Realistische bedrijfslogica en data-integriteit
- Moderne SaaS payment flow (Stripe Connect)

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
- **Silent failures** (fouten moeten gelogd worden)
- **Raw exceptions** tonen aan de eindgebruiker
- **Hardcoded credentials** of secrets in de codebase
- **Synoniemen**: Gebruik consistente domeintermen (Tenant, Order, Product).

## Verplicht
- Multi-database architectuur
- Stripe Connect
- CSS Variable injection voor theming
- **Services + Actions** pattern
- **Eager loading** (geen N+1 queries)
- **Pagination** op alle lijsten
- **Database Transactions** voor alle muterende operaties
- **Laravel Policies** voor alle autorisatie checks

---

# 3. Architectuur & Resilience

## 🔥 Tenant Safety & Authorization
- Data van tenants mag **NOOIT** gemengd worden.
- Gebruik **Laravel Policies** voor Products, Orders en Tenant data.
- Een gebruiker mag nooit data van een andere tenant zien of manipuleren.
- Admin toegang verloopt expliciet via middleware en policies.
- Indien tenant niet gevonden → onmiddellijk `abort(404)`.

## 🔥 Foutafhandeling (Error Handling)
- Gebruik **try/catch** blokken in Services en Actions.
- Gebruikers krijgen een vriendelijke, generieke melding.
- Technische fouten worden gelogd inclusief context (tenant, user, payload).

## 🔥 Logging Strategie
Gebruik Laravel logging (`Log::info`, `Log::error`) voor:
- **Errors**: Alle gecatchte exceptions inclusief stack trace.
- **Payments**: Elke statuswijziging van een transactie.
- **Belangrijke acties**: Tenant creation, orders, stock-mutaties.
- **Verplichte Context**: Alle logs **moeten** context bevatten zoals `tenant_id` en `user_id` (indien beschikbaar).

---

# 4. Service Layer & Business Patterns
- **Services**: Externe integraties (Stripe, Mail, etc.) worden hier afgehandeld.
- **Actions**: Business flows (bijv. `PlaceOrder`, `CreateTenant`) worden georkestreerd via Actions.
- **Thin Layers**: Controllers en Livewire components blijven "dun"; ze roepen enkel Actions/Services aan.

---

# 5. Performance, Concurrency & Extensibility

- **Performance**: Eager loading en Pagination zijn verplicht.
- **Concurrency**: Gebruik `lockForUpdate` bij stock updates en DB transactions voor integriteit.
- **Extensibility**:
  - Vermijd tight coupling tussen frontend en business logic.
  - De architectuur moet voorbereid zijn op API endpoints en mobiele clients.

---

# 6. Async & Webhooks

## 🔥 Queue & Async Processing
- Gebruik Laravel Queues voor e-mails, webhook verwerking en zware achtergrondtaken.
- Gebruik Jobs i.p.v. synchrone verwerking voor betere UX.

## 🔥 Webhook Management
- Webhooks moeten gevalideerd worden op **signature** en **idempotent** zijn.
- Vertrouw nooit blind op de webhook payload.

---

# 7. Business Logic & Data Integriteit

- **Stock Updates**: Moeten veilig en atomair gebeuren.
- **Order Immutability**: Gebruik snapshots voor prijs en productnaam in `order_items`.
- **Order Status**: Orders zijn immutable zodra ze 'paid' zijn.

---

# 8. Multi-Tenant Database & Migrations

- **Physical Isolation**: 1 Landlord DB + N Tenant DBs.
- **Migrations**:
  - Landlord migrations in `database/migrations/landlord`.
  - Tenant migrations in `database/migrations/tenant`.
  - Migrations moeten **idempotent** zijn.

---

# 9. Development & Seeding

- **Seeders**: Na `php artisan migrate:fresh --seed` moet het platform direct bruikbaar zijn voor demo's.
- Een seeder moet minstens bevatten:
  - 1 Landlord Admin.
  - 1 Actieve tenant met demo producten.
  - 1 Test customer account.

---

# 10. Theme System
- **CSS Variables**: Voor realtime styling.
*   **Layout Types**: Dynamische component-injectie.
*   **View Overrides**: Fallback mechanisme (`themes/{tenant}/` -> `themes/default/`).

---

# 11. Testing (Pest)
- Verplichte tests voor **Tenant Isolation**.
- Tests voor **Race Conditions** bij stock updates.
- Mocking van Stripe en Mail interfaces.

---

# 12. Hoofdregel
Bouw dit als een schaalbaar SaaS platform. Elke beslissing moet getoetst worden aan: **Is dit veilig, performant, traceerbaar en consistent?**
