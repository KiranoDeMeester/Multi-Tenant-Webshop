<?php

use App\Livewire\Storefront\Auth\Register;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Customer;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Fashion Shop',
        'slug' => 'fashionshop',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'fashionshop.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();
});

test('customer can register via storefront registration component', function () {
    Livewire::test(Register::class)
        ->set('name', 'Sarah Connor')
        ->set('email', 'sarah@resistance.org')
        ->set('phone', '+32 499 11 22 33')
        ->set('password', 'secret1234')
        ->set('password_confirmation', 'secret1234')
        ->call('register')
        ->assertRedirect(route('storefront.account'));

    expect(Customer::where('email', 'sarah@resistance.org')->exists())->toBeTrue();
    expect(Auth::guard('customer')->check())->toBeTrue();
    expect(Auth::guard('customer')->user()->name)->toBe('Sarah Connor');
});

test('customer registration validates duplicate email and password length', function () {
    Customer::create([
        'name' => 'Existing Customer',
        'email' => 'existing@example.com',
        'password' => bcrypt('password'),
    ]);

    Livewire::test(Register::class)
        ->set('name', 'New Customer')
        ->set('email', 'existing@example.com')
        ->set('password', 'short')
        ->set('password_confirmation', 'mismatch')
        ->call('register')
        ->assertHasErrors(['email', 'password']);
});
