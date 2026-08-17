# 🏛️ Systeemarchitectuur & Technisch Ontwerp

Dit document beschrijft de technische architectuur en ontwerpkeuzes van het **Multi-Tenant Webshop SaaS Platform**.

---

## 1. Multi-Tenancy Strategie: Database-per-Tenant

Het platform hanteert een **Multi-Database Architectuur (Database-per-Tenant)** met **SQLite** als opslagmedium.

```
                  ┌─────────────────────────────────┐
                  │      Inkomend HTTP Verzoek      │
                  │ (bijv. shop.localhost/product)  │
                  └────────────────┬────────────────┘
                                   │
                                   ▼
                  ┌─────────────────────────────────┐
                  │    SetTenantConnection (MW)     │
                  │   - Resolves Domain in Landlord │
                  │   - Configures dynamic DB path  │
                  │   - Purges/reconnects 'tenant'  │
                  └────────────────┬────────────────┘
                                   │
                  ┌────────────────┴────────────────┐
                  ▼                                 ▼
   ┌─────────────────────────────┐   ┌─────────────────────────────┐
   │    Landlord DB (Centraal)   │   │  Tenant DB (Geïsoleerd)     │
   │  - tenants                  │   │  - products & variations    │
   │  - domains                  │   │  - customers & addresses    │
   │  - platform users           │   │  - orders & order_items     │
   │  - contact_messages         │   │  - stock_mutations          │
   └─────────────────────────────┘   │  - settings                 │
                                     └─────────────────────────────┘
```

### Waarom Database-per-Tenant?
1. **Volledige Gegevensisolatie:** Onmogelijke data-leaks tussen verschillende winkels door fysieke bestandsscheiding.
2. **GDPR / Privacy Compliance:** Eenvoudig exporteren of wissen van een volledige webshop inclusief alle klantdata.
3. **Schaalbaarheid & Backups:** Iedere winkel kan individueel gebackupt, gemigreerd of gearchiveerd worden.

---

## 2. Authenticatie & Guard Scheiding

Om veilige scheiding van platform, winkelier en consument te garanderen, zijn 3 onafhankelijke authenticatie guards geconfigureerd:

| Guard | Model | Doelgroep | Scope & Bevoegdheid |
|---|---|---|---|
| `web` | `App\Models\User` | Platform Administrator | Beheert platformabonnementen, tenants en centrale logs |
| `tenant` | `App\Models\Tenant\User` | Winkelier / Merchant | Beheert catalogus, bestellingen, voorraad en winkelinstellingen |
| `customer` | `App\Models\Tenant\Customer` | Consument / Webshop Klant | Plaatst bestellingen, beheert afleveradressen en bekijkt orderhistorie |

### Policy Handhaving
- Elke query en Livewire actie valideert rechten via Laravel Policies (`ProductPolicy`, `OrderPolicy`, `CustomerAddressPolicy`, `CustomerPolicy`, `TenantPolicy`).
- Adreswijzigingen en order-annuleringen controleren strikt op eigenaarschap (`where('customer_id', $user->id)`), waarmee **Insecure Direct Object Reference (IDOR)** kwetsbaarheden volledig worden geëlimineerd.

---

## 3. Voorraadbeheer & Mutatie-Audit Engine (`StockService`)

Alle voorraadbewegingen lopen gecentraliseerd via `App\Services\StockService` binnen een database-transactie:

```
[Inkoop / Correctie / Verkoop / Annulering]
               │
               ▼
       DB::transaction()
       ├─ Lees stock_before
       ├─ Bereken stock_after (Valideer >= 0)
       ├─ Update Product of ProductVariation
       └─ Schrijf StockMutation auditlog record
```

### Mutatietypes:
- `purchase`: Inkoop van nieuwe voorraad door merchant.
- `sale`: Automatische afboeking bij voltooide bestelling (`FinalizeOrderAction`).
- `cancel_restitution`: Automatisch herstel van voorraad bij geannuleerde bestelling (`cancelOrder`).
- `adjustment`: Handmatige inventarisatiecorrectie.
- `return`: Geretourneerde artikelen.

---

## 4. Checkout, BTW-berekening & Stripe Flow

### Stappen in het Afrekenproces:
1. **Winkelwagen (`/winkelwagen`):** Bevat artikelen, geselecteerde varianten en realtime voorraadcontroles.
2. **Afrekenen (`/afrekenen`):**
   - Ingelogde klanten kiezen uit opgeslagen adressen of voeren een nieuw adres in.
   - Gastklanten voeren NAW-gegevens in met optie tot accountregistratie.
   - Factuuradres kan afwijken van bezorgadres.
3. **Order Initialisatie (`PrepareCheckoutAction`):**
   - Lokale `Order` en `OrderItem` snapshots worden aangemaakt met status `pending`.
   - BTW (21%) en verzendkosten worden berekend.
   - Stripe Checkout sessie wordt gegenereerd via `StripeService`.
4. **Betalingsverificatie (`StripeWebhookController` / `HandlePaymentAction`):**
   - Webhook verifieert Stripe handtekening.
   - Zoekt order op via `stripe_session_id` en markeert status als `paid`.
   - Roept `FinalizeOrderAction` aan: voorraad wordt afgeboekt via `StockService` en `OrderPaidMail` met aangehechte PDF-factuur wordt verzonden.
   - Idempotente logica voorkomt dubbele verwerking bij herhaalde webhook deliveries.

---

## 5. Facturatie (`InvoiceService`) & E-mail Notificaties

- **PDF Generatie:** `Barryvdh\DomPDF\Facade\Pdf` converteert de Blade template `pdf.invoice` naar een geoptimaliseerd A4 PDF-document.
- **BTW Specificatie:** Bevat uitsplitsing van subtotaal excl. BTW, 21% BTW-bedrag, verzendkosten en totaalbedrag incl. BTW.
- **Asynchrone E-mails (`ShouldQueue`):**
  - `OrderPaidMail` (Orderbevestiging + Factuur)
  - `OrderShippedMail` (Verzendbevestiging + Track & Trace)
  - `OrderCancelledMail` (Annuleringsbevestiging)
