<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandlordCheckoutController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Tenants\Index;
use App\Livewire\Landlord\RegisterTenant;
use App\Livewire\Storefront\Account\Addresses;
use App\Livewire\Storefront\Account\Orders;
use App\Livewire\Storefront\Account\Profile;
use App\Livewire\Storefront\Auth\ForgotPassword;
use App\Livewire\Storefront\Auth\Login;
use App\Livewire\Storefront\Auth\Register;
use App\Livewire\Storefront\Auth\ResetPassword;
use App\Livewire\Storefront\Checkout\Cancel;
use App\Livewire\Storefront\Checkout\Success;
use App\Livewire\Storefront\Pages\AboutUs;
use App\Livewire\Storefront\Pages\Collections;
use App\Livewire\Storefront\Pages\Contact;
use App\Livewire\Storefront\Pages\OrderTracking;
use App\Livewire\Storefront\Pages\PrivacyPolicy;
use App\Livewire\Storefront\Pages\Returns;
use App\Livewire\Storefront\Pages\Shipping;
use App\Livewire\Storefront\Pages\Terms;
use App\Livewire\Storefront\Products\Show;
use App\Livewire\Tenant\Dashboard\Payments;
use App\Livewire\Tenant\Products\Create;
use App\Livewire\Tenant\Products\Edit;
use App\Livewire\Tenant\Products\StockHistory;
use App\Livewire\Tenant\Settings\ComplianceSettings;
use App\Livewire\Tenant\Settings\InvoiceSettings;
use App\Livewire\Tenant\Settings\ShopSettings;
use App\Livewire\Tenant\Settings\StyleDashboard;
use Illuminate\Support\Facades\Route;

// Central Domain Routes (Platform Admin)
$centralDomains = [
    'platform.'.config('app.central_domain'),
    config('app.central_domain'),
    'localhost',
    '127.0.0.1',
];

foreach ($centralDomains as $domain) {
    Route::domain($domain)->middleware(['central'])->group(function () {
        Route::view('/', 'welcome')->name('home');
        Route::get('subscribe', [LandlordCheckoutController::class, 'subscribe'])->name('landlord.subscribe');
        Route::get('onboarding', RegisterTenant::class)->name('landlord.onboarding');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('dashboard', Dashboard::class)->name('dashboard');
            Route::get('admin/tenants', Index::class)->name('admin.tenants');
        });

        // Stripe Callback (must be on central domain and accessible to returning users)
        Route::get('stripe/callback', [StripeConnectController::class, 'callback'])->name('stripe.callback');

        Route::post('stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

        require base_path('vendor/laravel/fortify/routes/routes.php');

        require __DIR__.'/settings.php';
    });
}

// Tenant Domain Routes (Webshops)
Route::domain('{tenant}.'.config('app.central_domain', 'localhost'))->middleware(['tenant'])->group(function () {
    // Public Storefront
    Route::get('/', App\Livewire\Storefront\Products\Index::class)->name('storefront.products.index');
    Route::get('/categorie/{categorySlug}', App\Livewire\Storefront\Products\Index::class)->name('storefront.categories.show')->where('categorySlug', '[a-z0-9-]+');
    Route::get('/product/{slug}', Show::class)->name('storefront.products.show');
    Route::get('/winkelwagen', App\Livewire\Storefront\Cart\Index::class)->name('storefront.cart.index');
    Route::get('/mijn-account', App\Livewire\Storefront\Account\Dashboard::class)->name('storefront.account')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/bestellingen', Orders::class)->name('storefront.account.orders')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/bestellingen/{order}/factuur', [InvoiceController::class, 'downloadCustomerInvoice'])->name('storefront.account.orders.invoice')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/profiel', Profile::class)->name('storefront.account.profile')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/adressen', Addresses::class)->name('storefront.account.addresses')->middleware('auth:customer,tenant');

    // Info pages
    Route::get('/collecties', Collections::class)->name('storefront.pages.collections');
    Route::get('/over-ons', AboutUs::class)->name('storefront.pages.about-us');
    Route::get('/verzending', Shipping::class)->name('storefront.pages.shipping');
    Route::get('/retourneren', Returns::class)->name('storefront.pages.returns');
    Route::get('/privacy', PrivacyPolicy::class)->name('storefront.pages.privacy');
    Route::get('/voorwaarden', Terms::class)->name('storefront.pages.terms');
    Route::get('/contact', Contact::class)->name('storefront.pages.contact');
    Route::get('/bestelling/volgen/{id}', OrderTracking::class)->name('storefront.order.track');

    // Checkout flow
    Route::get('/afrekenen', App\Livewire\Storefront\Checkout\Index::class)->name('storefront.checkout');
    Route::get('/checkout/success', Success::class)->name('storefront.checkout.success');
    Route::get('/checkout/cancel', Cancel::class)->name('storefront.checkout.cancel');

    // Auth Routes for customers
    Route::get('/login', Login::class)->name('storefront.login');
    Route::get('/registreren', Register::class)->name('storefront.register');
    Route::get('/wachtwoord-vergeten', ForgotPassword::class)->name('storefront.password.request');
    Route::get('/wachtwoord-resetten/{token}', ResetPassword::class)->name('storefront.password.reset');

    Route::post('/logout', function () {
        $wasTenant = auth('tenant')->check();

        auth('tenant')->logout();
        auth('web')->logout();
        auth('customer')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        if ($wasTenant) {
            return redirect()->route('tenant.login');
        }

        return redirect()->route('storefront.products.index');
    })->name('tenant.logout');

    // Tenant Dashboard (Merchant View)
    Route::prefix('dashboard')->group(function () {
        Route::get('login', function () {
            return redirect()->route('storefront.login');
        })->name('tenant.login')->middleware('guest:tenant');

        Route::middleware(['auth:tenant'])->group(function () {
            Route::get('/', App\Livewire\Tenant\Dashboard\Index::class)->name('tenant.dashboard');

            // Management routes
            Route::get('/products', App\Livewire\Tenant\Products\Index::class)->name('tenant.products.manage');
            Route::get('/products/create', Create::class)->name('tenant.products.create');
            Route::get('/products/stock-history', StockHistory::class)->name('tenant.products.stock-history');
            Route::get('/products/{product}/edit', Edit::class)->name('tenant.products.edit');
            Route::get('/categories', App\Livewire\Tenant\Categories\Index::class)->name('tenant.categories.index');
            Route::get('/categories/create', App\Livewire\Tenant\Categories\Create::class)->name('tenant.categories.create');
            Route::get('/categories/{category}/edit', App\Livewire\Tenant\Categories\Edit::class)->name('tenant.categories.edit');
            Route::get('/orders', App\Livewire\Tenant\Orders\Index::class)->name('tenant.orders.index');
            Route::get('/orders/{order}', App\Livewire\Tenant\Orders\Show::class)->name('tenant.orders.show');
            Route::get('/orders/{order}/edit', App\Livewire\Tenant\Orders\Edit::class)->name('tenant.orders.edit');
            Route::get('/orders/{order}/invoice', [InvoiceController::class, 'downloadMerchantInvoice'])->name('tenant.orders.invoice');
            Route::get('/coupons', App\Livewire\Tenant\Coupons\Index::class)->name('tenant.coupons.index');
            Route::get('/customers', App\Livewire\Tenant\Customers\Index::class)->name('tenant.customers.index');
            Route::get('/customers/{customer}', App\Livewire\Tenant\Customers\Show::class)->name('tenant.customers.show');
            Route::get('/settings', StyleDashboard::class)->name('tenant.settings');
            Route::get('/settings/shop', ShopSettings::class)->name('tenant.settings.shop');
            Route::get('/settings/invoice', InvoiceSettings::class)->name('tenant.settings.invoice');
            Route::get('/settings/compliance', ComplianceSettings::class)->name('tenant.settings.compliance');
            Route::get('/payments', Payments::class)->name('tenant.payments');
            Route::get('/stripe/connect', [StripeConnectController::class, 'redirect'])->name('stripe.connect');
        });
    });
});
