<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\StripeWebhookController;

// Central Domain Routes (Platform Admin)
$centralDomains = [
    'platform.' . config('app.central_domain'),
    config('app.central_domain'),
    'localhost',
    '127.0.0.1'
];

foreach ($centralDomains as $domain) {
    Route::domain($domain)->middleware(['central'])->group(function () {
        Route::view('/', 'welcome')->name('home');
        Route::get('subscribe', [\App\Http\Controllers\LandlordCheckoutController::class, 'subscribe'])->name('landlord.subscribe');
        Route::get('onboarding', \App\Livewire\Landlord\RegisterTenant::class)->name('landlord.onboarding');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
            Route::get('admin/tenants', \App\Livewire\Admin\Tenants\Index::class)->name('admin.tenants');
        });

        // Stripe Callback (must be on central domain and accessible to returning users)
        Route::get('stripe/callback', [StripeConnectController::class, 'callback'])->name('stripe.callback');

        Route::post('stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

        require base_path('vendor/laravel/fortify/routes/routes.php');

        require __DIR__.'/settings.php';
    });
}

// Tenant Domain Routes (Webshops)
Route::domain('{tenant}.' . config('app.central_domain', 'localhost'))->middleware(['tenant'])->group(function () {
    // Public Storefront
    Route::get('/', \App\Livewire\Storefront\Products\Index::class)->name('storefront.products.index');
    Route::get('/categorie/{categorySlug}', \App\Livewire\Storefront\Products\Index::class)->name('storefront.categories.show')->where('categorySlug', '[a-z0-9-]+');
    Route::get('/product/{slug}', \App\Livewire\Storefront\Products\Show::class)->name('storefront.products.show');
    Route::get('/winkelwagen', \App\Livewire\Storefront\Cart\Index::class)->name('storefront.cart.index');
    Route::get('/mijn-account', \App\Livewire\Storefront\Account\Dashboard::class)->name('storefront.account')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/bestellingen', \App\Livewire\Storefront\Account\Orders::class)->name('storefront.account.orders')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/profiel', \App\Livewire\Storefront\Account\Profile::class)->name('storefront.account.profile')->middleware('auth:customer,tenant');
    Route::get('/mijn-account/adressen', \App\Livewire\Storefront\Account\Addresses::class)->name('storefront.account.addresses')->middleware('auth:customer,tenant');
    
    // Info pages
    Route::get('/collecties', \App\Livewire\Storefront\Pages\Collections::class)->name('storefront.pages.collections');
    Route::get('/over-ons', \App\Livewire\Storefront\Pages\AboutUs::class)->name('storefront.pages.about-us');
    Route::get('/verzending', \App\Livewire\Storefront\Pages\Shipping::class)->name('storefront.pages.shipping');
    Route::get('/retourneren', \App\Livewire\Storefront\Pages\Returns::class)->name('storefront.pages.returns');
    Route::get('/privacy', \App\Livewire\Storefront\Pages\PrivacyPolicy::class)->name('storefront.pages.privacy');
    Route::get('/voorwaarden', \App\Livewire\Storefront\Pages\Terms::class)->name('storefront.pages.terms');

    // Checkout flow
    Route::get('/checkout/success', \App\Livewire\Storefront\Checkout\Success::class)->name('storefront.checkout.success');
    Route::get('/checkout/cancel', \App\Livewire\Storefront\Checkout\Cancel::class)->name('storefront.checkout.cancel');

    // Auth Routes for customers
    Route::get('/login', \App\Livewire\Storefront\Auth\Login::class)->name('storefront.login');
    
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
        Route::get('login', function() {
            return redirect()->route('storefront.login');
        })->name('tenant.login')->middleware('guest:tenant');
        
        Route::middleware(['auth:tenant'])->group(function () {
            Route::get('/', \App\Livewire\Tenant\Dashboard\Index::class)->name('tenant.dashboard');
            
            // Management routes
            Route::get('/products', \App\Livewire\Tenant\Products\Index::class)->name('tenant.products.manage');
            Route::get('/products/create', \App\Livewire\Tenant\Products\Create::class)->name('tenant.products.create');
            Route::get('/products/{product}/edit', \App\Livewire\Tenant\Products\Edit::class)->name('tenant.products.edit');
            Route::get('/categories', \App\Livewire\Tenant\Categories\Index::class)->name('tenant.categories.index');
            Route::get('/categories/create', \App\Livewire\Tenant\Categories\Create::class)->name('tenant.categories.create');
            Route::get('/categories/{category}/edit', \App\Livewire\Tenant\Categories\Edit::class)->name('tenant.categories.edit');
            Route::get('/orders', \App\Livewire\Tenant\Orders\Index::class)->name('tenant.orders.index');
            Route::get('/orders/{order}', \App\Livewire\Tenant\Orders\Show::class)->name('tenant.orders.show');
            Route::get('/orders/{order}/edit', \App\Livewire\Tenant\Orders\Edit::class)->name('tenant.orders.edit');
            Route::get('/customers', \App\Livewire\Tenant\Customers\Index::class)->name('tenant.customers.index');
            Route::get('/customers/{customer}', \App\Livewire\Tenant\Customers\Show::class)->name('tenant.customers.show');
            Route::get('/settings', \App\Livewire\Tenant\Settings\StyleDashboard::class)->name('tenant.settings');
            Route::get('/settings/shop', \App\Livewire\Tenant\Settings\ShopSettings::class)->name('tenant.settings.shop');
            Route::get('/settings/invoice', \App\Livewire\Tenant\Settings\InvoiceSettings::class)->name('tenant.settings.invoice');
            Route::get('/settings/compliance', \App\Livewire\Tenant\Settings\ComplianceSettings::class)->name('tenant.settings.compliance');
            Route::get('/payments', \App\Livewire\Tenant\Dashboard\Payments::class)->name('tenant.payments');
            Route::get('/stripe/connect', [StripeConnectController::class, 'redirect'])->name('stripe.connect');
        });
    });
});

