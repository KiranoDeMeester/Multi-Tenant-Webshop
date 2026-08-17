<?php

use App\Livewire\Landlord\RegisterTenant;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();
});

test('onboarding screen requires paid stripe session or local bypass', function () {
    Livewire::test(RegisterTenant::class)
        ->assertSet('isPaid', false)
        ->assertSet('errorMessage', 'Geen betalingssessie gevonden. Betaal eerst het abonnement.');
});

test('onboarding screen allows registering tenant', function () {
    // Simulate paid state
    $component = Livewire::test(RegisterTenant::class);
    $component->set('isPaid', true);

    $component->set('shop_name', 'Test Boutique')
        ->set('subdomain', 'testboutique')
        ->set('admin_name', 'Admin User')
        ->set('admin_email', 'admin@example.com')
        ->set('admin_password', 'password123')
        ->call('register')
        ->assertHasNoErrors();

    // Verify Tenant and Domain created in landlord database
    expect(Tenant::count())->toBe(1);
    expect(Domain::count())->toBe(1);

    $tenant = Tenant::first();
    expect($tenant->name)->toBe('Test Boutique');
    expect(Domain::first()->domain)->toBe('testboutique.localhost');
});
