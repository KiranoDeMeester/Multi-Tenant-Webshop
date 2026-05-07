<?php

use Illuminate\Support\Facades\Route;

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

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::view('dashboard', 'dashboard')->name('dashboard');
            Route::get('admin/tenants', \App\Livewire\Admin\Tenants\Index::class)->name('admin.tenants');
        });

        require base_path('vendor/laravel/fortify/routes/routes.php');

        require __DIR__.'/settings.php';
    });
}

// Tenant Domain Routes (Webshops)
Route::domain('{tenant}.' . config('app.central_domain', 'localhost'))->middleware(['tenant'])->group(function () {
    // Public Storefront
    Route::get('/', function () {
        return view('welcome_tenant');
    })->name('tenant.home');

    // Tenant Dashboard (Step 12b)
    Route::prefix('dashboard')->group(function () {
        Route::get('login', \App\Livewire\Tenant\Dashboard\Login::class)->name('tenant.login')->middleware('guest:tenant');
        
        Route::middleware(['auth:tenant'])->group(function () {
            Route::get('/', \App\Livewire\Tenant\Dashboard\Index::class)->name('tenant.dashboard');
            
            // Management routes
            Route::get('/products', \App\Livewire\Tenant\Products\Index::class)->name('tenant.products.index');
            Route::get('/products/create', \App\Livewire\Tenant\Products\Create::class)->name('tenant.products.create');
            Route::get('/products/{product}/edit', \App\Livewire\Tenant\Products\Edit::class)->name('tenant.products.edit');
            Route::get('/categories', function() { return 'Categorieën Overzicht (Coming Soon)'; })->name('tenant.categories.index');
            Route::get('/orders', function() { return 'Bestellingen Overzicht (Coming Soon)'; })->name('tenant.orders.index');
            Route::get('/customers', function() { return 'Klanten Overzicht (Coming Soon)'; })->name('tenant.customers.index');
            Route::get('/settings', \App\Livewire\Tenant\Settings\StyleDashboard::class)->name('tenant.settings');
        });
    });
});

