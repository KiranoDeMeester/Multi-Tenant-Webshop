# Project Roadmap — Multi-Tenant Webshop

This file tracks the implementation steps. Each step corresponds to a GitHub Issue.

## Phase 1: Core Multi-Database Infrastructure

- [x] **Step 01: Landlord Model Architecture**
  - Setup the `Landlord` database connection.
  - Create `Tenant` and `Domain` models + migrations.
  - Implement a basic `TenantResource` in the platform admin.

- [x] **Step 02: Dynamic DB Connection Switcher**
  - Create `TenantManager` service.
  - Implement runtime connection switching logic.
  - Add `SetTenantConnection` middleware.

- [x] **Step 03: Tenant Database Provisioning**
  - Create `CreateTenantDatabaseAction`.
  - Implement raw SQL logic to create isolated databases and users.

- [x] **Step 04: The Multi-Tenant Migrator**
  - Build the `tenants:migrate` Artisan command.
  - Setup separate migration paths for `database/migrations/landlord` and `database/migrations/tenant`.

- [x] **Step 05: Global Tenant Context & Helper Functions**
  - Create helper functions to access current tenant data (`tenant()`).
  - Implement a `TenantScope` for any shared platform models (if any).

## Phase 2: Domain, Routing & Multi-Auth

- [x] **Step 06: Wildcard Subdomain Routing**
  - Configure Laravel to handle `{tenant}.localhost` and `platform.localhost`.
  - Implement a `CentralDomain` check to prevent tenants from accessing the landlord admin.

- [x] **Step 07: Platform Admin Auth (Landlord)**
  - Setup authentication for the platform owners (the people who manage the SaaS).

- [x] **Step 08: Tenant Customer Auth (Storefront)**
  - Implement a custom Auth Guard for customers *within* a specific tenant database.
  - Ensure a customer of `Tenant A` cannot log into `Tenant B`.

## Phase 3: Tenant Business Logic (Core)

- [x] **Step 09: Category & Product Schema**
  - Create Tenant-side migrations for `categories` and `products`.
  - Implement soft deletes and UUIDs for public-facing IDs.

- [x] **Step 10: Media Management (Images)**
  - Implement Spatie Media Library (or custom) for product images.
  - Setup disk partitioning (e.g., `storage/app/public/tenants/{id}/media`).

- [x] **Step 11: Product Variations (Colors/Sizes)**
  - Implement a `ProductAttribute` system for variations.
  - Logic for different prices per variation.

- [ ] **Step 12: Stock & Inventory Control**
  - Add stock tracking to products.
  - Implement "Out of stock" logic in the frontend.

## Phase 4: The Storefront & Theming

- [ ] **Step 13: Dynamic CSS Variable Injection**
  - Create a "Style Dashboard" for tenants to pick colors/fonts.
  - Inject these into the root layout via Blade.

- [ ] **Step 14: Layout Selection System**
  - Implement multiple layout components (Modern vs. Minimal).
  - Allow tenants to toggle features (e.g., "Show Hero Banner", "Newsletter Popup").

- [ ] **Step 15: Storefront: Product Catalog**
  - Build the listing page with Livewire (Filters for categories, price, search).
  - Implement the "Product Details" page.

- [ ] **Step 16: Interactive Shopping Cart**
  - Build a persistent database-driven or session-based cart system.
  - Add Livewire "Side-cart" animations.

## Phase 5: Payments & Stripe Connect (The "Money" Phase)

- [ ] **Step 17: Stripe Connect Onboarding Flow**
  - Build the "Connect with Stripe" dashboard for tenants.
  - Handle the OAuth callback and secure the `stripe_account_id`.

- [ ] **Step 18: Checkout Engine**
  - Implement Stripe Checkout sessions.
  - Use "Destination Charges" to route money to tenants while taking a platform fee.

- [ ] **Step 19: Webhook Global Handler**
  - Build a system that listens to all Stripe webhooks and routes them to the specific tenant's `HandlePaymentAction`.

- [ ] **Step 20: Order Management & Logic**
  - Implement the "Success" flow: Mark paid, update stock, notify customer.

## Phase 6: Advanced Business Features

- [ ] **Step 21: PDF Invoicing**
  - Generate professional PDF invoices per order with tenant branding.
  - Automated email sending of invoices.

- [ ] **Step 22: Tenant Sales Analytics Dashboard**
  - Build charts (Chart.js) for Sales Volume, Top Products, and Customer Growth.

- [ ] **Step 23: Customer Order History**
  - Build a "My Account" area for storefront customers.

- [ ] **Step 24: SEO & Meta Tag Management**
  - Allow tenants to set custom Meta Titles and Descriptions for every product/page.

## Phase 7: Final Polish & Deployment

- [ ] **Step 25: GDPR & Cookie Compliance**
  - Add a configurable cookie banner that adapts to tenant colors.
  - Auto-generate a "Privacy Policy" template for tenants.

- [ ] **Step 26: Automated Testing Suite**
  - Comprehensive Pest tests for Database Isolation (Critical!).
  - Integration tests for the Checkout flow.

- [ ] **Step 27: Final UI Audit & Demo Data**
  - Polish the Landlord admin.
  - Create a "Demo Seeder" that generates a beautiful shop with sample products.

