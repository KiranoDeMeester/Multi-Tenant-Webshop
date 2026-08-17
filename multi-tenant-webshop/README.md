# 🏪 Multi-Tenant E-Commerce Platform

Een volwaardig, enterprise-grade **Multi-Tenant Webshop SaaS Platform** gebouwd met **Laravel 12**, **Livewire 4**, **Flux UI**, **Tailwind CSS**, **SQLite (Database-per-Tenant)** en **Stripe Connect**.

Dit project biedt een complete SaaS-oplossing waarbij platformbeheerders nieuwe webshops (tenants) kunnen onboarden en beheren, winkeliers (merchants) hun eigen catalogus, variaties, voorraadaudits en bestellingen beheren via een gepersonaliseerd dashboard, en storefront klanten naadloos kunnen winkelen, registreren, en afrekenen.

---

## 🌟 Belangrijkste Functionaliteiten

### 1. 🏢 Multi-Tenant Architectuur (Database-per-Tenant)
- **Volledige Database Isolatie:** Elke webshop krijgt een eigen afzonderlijke SQLite database (`database/tenants/{slug}.sqlite`), waardoor data van verschillende winkels nooit vermengd raakt.
- **Centraal Landlord Beheer:** Landlord database beheert tenants, domeinen/subdomeinen, abonnementen en platform-wijde contactberichten.
- **Dynamische Context Switching:** `TenantManager` en `SetTenantConnection` middleware wisselen automatisch van databaseverbinding op basis van het subdomein/host.

### 2. 🔐 Autorisatie, Beveiliging & IDOR-bescherming
- **3 Aparte Authenticatie Guards:**
  - `web`: Platform/Landlord Superadministrators (`App\Models\User`).
  - `tenant`: Winkelier/Merchant Beheerders (`App\Models\Tenant\User`).
  - `customer`: Storefront Klanten & Kopers (`App\Models\Tenant\Customer`).
- **Granulaire Laravel Policies:**
  - `ProductPolicy`: Beschermt CRUD operaties tegen ongeautoriseerde toegang.
  - `OrderPolicy`: Garandeert dat klanten uitsluitend hun eigen bestellingen kunnen bekijken of annuleren.
  - `CustomerPolicy` & `CustomerAddressPolicy`: Volledige IDOR-bescherming op klantprofielen en adresboeken.
  - `TenantPolicy`: Beveiligt platform-operaties voorbehouden aan landlord admins.

### 3. 🛍️ Storefront E-Commerce Flow & Checkout
- **Klantregistratie & Login:** Klanten kunnen direct registreren via de storefront (`/registreren`) of inloggen (`/login`).
- **Productvariaties & Matrix:** Ondersteuning voor maten, kleuren en opties met eigen SKU, afwijkende prijzen en voorraadniveaus.
- **Interactieve Keuzeknoppen:** Storefront toont dynamische prijs- en voorraadupdates op basis van gekozen varianten.
- **Volledig Checkout Proces (`/afrekenen`):**
  - **Klant:** Keuze uit opgeslagen bezorgadressen of toevoegen van een nieuw adres aan het adresboek.
  - **Gast:** Volledige adresinvoer met optie "Account aanmaken met wachtwoord".
  - **Factuuradres:** Schakelaar "Factuuradres is hetzelfde als afleveradres".
  - **Besteloverzicht:** Transparante weergave van producten, varianten, BTW (21%), verzendkosten en totaalbedrag.

### 4. 📦 Geavanceerd Voorraadbeheer & Audit Trail
- **`StockMutation` Auditlogboek:** Elke voorraadwijziging wordt geregistreerd met type (`purchase`, `sale`, `adjustment`, `return`, `cancel_restitution`), aantal, voorraad vóór/na en toelichting/ordernummer.
- **Automatische Verkoopafhandeling:** Voorraad wordt automatisch en atomair afgeboekt bij succesvolle betaling via `StockService`.
- **Automatisch Voorraadherstel:** Bij annulering van een bestelling door de klant of winkelier wordt de voorraad automatisch hersteld en gelogd als `cancel_restitution`.
- **Dashboard Voorraadhistorie:** Inzichtelijk overzicht voor winkeliers met filteropties en handmatige voorraadcorrecties.

### 5. 🧾 Facturatie, Webhooks & Statusmails
- **PDF Factuur Generatie:** Volledige PDF facturen (`Barryvdh\DomPDF`) met merchant bedrijfsgegevens, BTW-nummer, klantadressen, transactiecode en gespecificeerde BTW-berekening (21%).
- **Factuur Downloads:** Zowel klanten (`/mijn-account/bestellingen/{order}/factuur`) als winkeliers (`/dashboard/orders/{order}/invoice`) kunnen facturen direct downloaden.
- **Geautomatiseerde E-mails:**
  - `OrderPaidMail`: Bevestigingsmail met aangehechte PDF-factuur.
  - `OrderShippedMail`: Verzendbevestiging inclusief Track & Trace.
  - `OrderCancelledMail`: Annuleringsbevestiging met vermelding van eventuele terugbetaling.
- **Idempotente Stripe Webhook:** Veilige en idempotente afhandeling van Stripe events (`checkout.session.completed`) met dubbele betalingsdetectie.

---

## 🏗️ Technische Stack

| Component | Technologie |
|---|---|
| **Framework** | Laravel 12 (PHP 8.3+) |
| **Frontend/Reactivity** | Livewire 4 & Livewire Flux UI |
| **Database** | SQLite (Landlord + Dedicated Database-per-Tenant) |
| **Betalingen** | Stripe Connect & Stripe Checkout |
| **Documenten/PDF** | DomPDF (`barryvdh/laravel-dompdf`) |
| **Media/Afbeeldingen** | Spatie Laravel MediaLibrary & Intervention Image |
| **Testing** | Pest 4 & PHPUnit |
| **Code Formatting** | Laravel Pint (PSR-12 Standard) |

---

## 🚀 Lokale Installatie & Setup

### 1. Vereisten
- PHP >= 8.3 met SQLite en GD/Imagick extensies
- Composer
- Node.js (>= 20) & NPM

### 2. Repository Klonen
```bash
git clone https://github.com/KiranoDeMeester/Multi-Tenant-Webshop.git
cd Multi-Tenant-Webshop/multi-tenant-webshop
```

### 3. Dependencies Installeren
```bash
composer install
npm install
```

### 4. Configuratie (.env)
```bash
cp .env.example .env
php artisan key:generate
```

Controleer in `.env` de centrale domeininstelling:
```dotenv
CENTRAL_DOMAIN=localhost
DB_CONNECTION=landlord
LANDLORD_DB_DATABASE="database/database.sqlite"
```

### 5. Databases Migreren & Seeden
```bash
# Maak database bestanden aan indien nodig
touch database/database.sqlite

# Migreer landlord en seed demo tenants
php artisan migrate --path=database/migrations/landlord
php artisan db:seed
```

### 6. Development Server Starten
```bash
# Terminal 1: Vite Assets
npm run dev

# Terminal 2: Laravel Server
php artisan serve
```

---

## 🧪 Test Suite Uitvoeren

Het project beschikt over een uitgebreide testsuite met **82 geautomatiseerde tests** en **253 assertions** (100% pass rate):

```bash
php vendor/bin/pest
```

### Belangrijkste Testsuites:
- `tests/Feature/PolicyAuthorizationTest.php`: Autorisatie policies, guard scheiding & IDOR verificatie.
- `tests/Feature/CustomerRegistrationTest.php`: Klantregistratie en validatie.
- `tests/Feature/ProductVariationManagementTest.php`: Variatiebeheer in dashboard & variantenselectie in webshop.
- `tests/Feature/StorefrontCheckoutAddressFlowTest.php`: Adresselectie, gast checkout en order creatie.
- `tests/Feature/StockMutationInventoryTest.php`: Voorraadaudit logboek, afboeking en herstel.
- `tests/Feature/InvoiceAndOrderEmailsTest.php`: BTW factuurgeneratie en transactionele e-mails.
- `tests/Feature/StripeWebhookIdempotencyTest.php`: Webhook deduplicatie en idempotentie.

---

## 📁 Projectstructuur

```
multi-tenant-webshop/
├── app/
│   ├── Actions/Tenant/          # Checkout, Order finalizing, Betalingsafhandeling
│   ├── Http/
│   │   ├── Controllers/         # InvoiceController, WebhookController, StripeConnect
│   │   └── Middleware/          # SetTenantConnection, EnsureTenantActive
│   ├── Livewire/
│   │   ├── Admin/               # Landlord platform dashboard & tenant beheer
│   │   ├── Storefront/          # Products, Cart, Checkout, Auth, Account
│   │   └── Tenant/              # Merchant Dashboard, Products, Orders, Stock, Settings
│   ├── Mail/                    # OrderPaidMail, OrderShippedMail, OrderCancelledMail
│   ├── Models/
│   │   ├── Landlord/            # Tenant, Domain, ContactMessage
│   │   └── Tenant/              # Product, Variation, Order, StockMutation, Customer, etc.
│   ├── Policies/                # ProductPolicy, OrderPolicy, CustomerPolicy, TenantPolicy
│   └── Services/                # TenantManager, CartService, StockService, InvoiceService
├── database/
│   ├── migrations/
│   │   ├── landlord/            # Landlord centrale tabellen
│   │   └── tenant/              # Dynamische tenant tabellen
│   └── tenants/                 # SQLite databases per actieve webshop
├── resources/views/
│   ├── emails/orders/           # E-mail templates (paid, shipped, cancelled)
│   ├── layouts/                 # Storefront, Tenant dashboard & Landlord layouts
│   ├── livewire/                # Livewire views voor storefront, merchant & admin
│   └── pdf/                     # Factuur PDF template (HTML/DomPDF)
└── tests/
    └── Feature/                 # 82 Pest Feature & Integration tests
```

---

## 📄 Licentie
Dit project is open-source software gelicenseerd onder de [MIT licentie](LICENSE).
