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

        require __DIR__.'/settings.php';
    });
}

// Tenant Domain Routes (Webshops)
Route::domain('{tenant}.' . config('app.central_domain'))->group(function () {
    Route::get('/', function ($tenant) {
        return "Welkom bij webshop: " . $tenant;
    });
});

