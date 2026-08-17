# 🏪 Multi-Tenant E-Commerce Platform

[![CI Pipeline](https://github.com/KiranoDeMeester/Multi-Tenant-Webshop/actions/workflows/ci.yml/badge.svg)](https://github.com/KiranoDeMeester/Multi-Tenant-Webshop/actions)
[![Tests](https://img.shields.io/badge/tests-89%20passed%20(281%20assertions)-brightgreen.svg)](https://github.com/KiranoDeMeester/Multi-Tenant-Webshop)
[![PHP](https://img.shields.io/badge/php-8.3%2B-blue.svg)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-12.x-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Een volwaardig, enterprise-grade **Multi-Tenant Webshop SaaS Platform** gebouwd met **Laravel 12**, **Livewire 4**, **Flux UI**, **Tailwind CSS**, **SQLite (Database-per-Tenant)**, **Stripe Connect**, **Docker** en **GitHub Actions CI/CD**.

Dit project biedt een complete SaaS-oplossing waarbij platformbeheerders nieuwe webshops (tenants) kunnen onboarden en beheren, winkeliers (merchants) hun eigen catalogus, variaties, kortingscodes, voorraadaudits en bestellingen beheren via een gepersonaliseerd dashboard, en storefront klanten naadloos kunnen winkelen, registreren, en afrekenen.

---

## 🌟 Belangrijkste Functionaliteiten

### 1. 🏢 Multi-Tenant Architectuur (Database-per-Tenant)
- **Volledige Database Isolatie:** Elke webshop krijgt een eigen afzonderlijke SQLite database (`database/tenants/{slug}.sqlite`), waardoor data van verschillende winkels nooit vermengd raakt.
- **Centraal Landlord Beheer:** Landlord database beheert tenants, domeinen/subdomeinen, abonnementen en platform-wijde contactberichten.
- **Performance Caching:** `SetTenantConnection` middleware cacht domeinresoluties via `Cache::remember()`, wat zorgt voor 0 landlord queries bij herhaalde storefront bezoeken.

### 2. 🔐 Autorisatie, Beveiliging & IDOR-bescherming
- **3 Aparte Authenticatie Guards:**
  - `web`: Platform/Landlord Superadministrators (`App\Models\User`).
  - `tenant`: Winkelier/Merchant Beheerders (`App\Models\Tenant\User`).
  - `customer`: Storefront Klanten & Kopers (`App\Models\Tenant\Customer`).
- **Granulaire Laravel Policies:**
  - `ProductPolicy`, `OrderPolicy`, `CustomerPolicy`, `CustomerAddressPolicy`, `TenantPolicy`.
- **Customer Password Reset:** Dedicated wachtwoord-herstel flow met beveiligde tokens en mails voor storefront klanten (`/wachtwoord-vergeten`).

### 3. 🛍️ Storefront E-Commerce Flow, Checkout & Kortingscodes
- **Klantregistratie & Login:** Direct registreren via `/registreren` of inloggen via `/login`.
- **Productvariaties & Matrix:** Maten, kleuren en opties met eigen SKU, afwijkende prijzen en voorraadniveaus met live UI updates.
- **Kortingscodes & Coupons:** Klanten kunnen kortingscodes toepassen in de checkout met live validatie en BTW-herberekening.
- **Volledig Checkout Proces (`/afrekenen`):**
  - Opgeslagen bezorgadressen voor klanten of gast-adresinvoer met optie tot accountcreatie.
  - Schakelaar voor afwijkend factuuradres.
  - Transparant besteloverzicht met 21% BTW-uitsplitsing, korting en verzendkosten.

### 4. 📦 Geavanceerd Voorraadbeheer & Audit Trail
- **`StockMutation` Auditlogboek:** Elke voorraadmutatie wordt geregistreerd (`purchase`, `sale`, `adjustment`, `return`, `cancel_restitution`) met `stock_before` en `stock_after`.
- **Automatische Verkoopafhandeling & Herstel:** Voorraad wordt atomisch afgeboekt bij betaling via `StockService` en direct hersteld bij orderannulering.
- **Dashboard Voorraadhistorie:** Inzichtelijk overzicht voor winkeliers met filteropties en handmatige voorraadcorrecties.

### 5. 🧾 Facturatie, Webhooks & Statusmails
- **PDF Factuur Generatie:** Volledige PDF facturen (`Barryvdh\DomPDF`) met merchant bedrijfsgegevens, BTW-nummer, klantadressen en 21% BTW-uitsplitsing.
- **Factuur Downloads:** Zowel klanten (`/mijn-account/bestellingen/{order}/factuur`) als winkeliers (`/dashboard/orders/{order}/invoice`) kunnen facturen direct downloaden.
- **Geautomatiseerde E-mails:** `OrderPaidMail` (inclusief PDF factuur), `OrderShippedMail` (Track & Trace) en `OrderCancelledMail`.
- **Idempotente Stripe Webhook:** Veilige afhandeling van Stripe events (`checkout.session.completed`) met deduplicatie.

### 6. ⚙️ DevOps, CI/CD & Data Portabiliteit
- **GitHub Actions CI/CD:** Geautomatiseerde pipeline die Pint (style check) en de complete Pest testsuite draait bij elke commit.
- **Docker Stack:** Kant-en-klare `Dockerfile` en `docker-compose.yml` met Nginx en PHP 8.3-FPM.
- **GDPR Data Portabiliteit / Backup:** Artisan commando `php artisan tenant:backup {slug}` dat de database en media in een `.zip` archief inpakt.

---

## 🏗️ Technische Stack

| Component | Technologie |
|---|---|
| **Framework** | Laravel 12 (PHP 8.3+) |
| **Frontend/Reactivity** | Livewire 4 & Livewire Flux UI |
| **Database** | SQLite (Landlord + Dedicated Database-per-Tenant) |
| **Betalingen** | Stripe Connect & Stripe Checkout |
| **Documenten/PDF** | DomPDF (`barryvdh/laravel-dompdf`) |
| **DevOps / CI/CD** | GitHub Actions & Docker Compose |
| **Testing** | Pest 4 & PHPUnit (89 tests, 281 assertions) |
| **Code Formatting** | Laravel Pint (PSR-12 Standard) |

---

## 🚀 Snelle Start (Met Docker of Lokaal)

### Optie A: Met Docker (Aanbevolen)
```bash
# Start de volledige container stack
docker compose up -d

# De webshop draait direct op http://localhost:8000
```

### Optie B: Lokale Installatie
```bash
git clone https://github.com/KiranoDeMeester/Multi-Tenant-Webshop.git
cd Multi-Tenant-Webshop/multi-tenant-webshop

composer install
npm install
cp .env.example .env
php artisan key:generate

# Migreer landlord en start servers
php artisan migrate --path=database/migrations/landlord
php artisan db:seed

npm run dev
php artisan serve
```

---

## 🧪 Test Suite Uitvoeren

Het project beschikt over een uitgebreide testsuite met **89 geautomatiseerde tests** en **281 assertions** (100% pass rate):

```bash
php vendor/bin/pest
```

---

## 📄 Licentie
Dit project is open-source software gelicenseerd onder de [MIT licentie](LICENSE).
