<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('admin/tenants', \App\Livewire\Admin\Tenants\Index::class)->name('admin.tenants');
});

require __DIR__.'/settings.php';
