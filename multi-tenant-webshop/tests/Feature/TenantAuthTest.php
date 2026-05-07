<?php

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Customer;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Migrate landlord tables
    $this->artisan('migrate', [
        '--path' => 'database/migrations/landlord',
        '--realpath' => true,
    ]);

    // Setup two tenants for testing isolation
    $this->tenantA = Tenant::create([
        'name' => 'Shop A',
        'db_name' => 'tenant_a_test_' . uniqid(),
    ]);

    $this->tenantB = Tenant::create([
        'name' => 'Shop B',
        'db_name' => 'tenant_b_test_' . uniqid(),
    ]);

    // Create databases for these tenants (using sqlite in memory for speed in tests is tricky with multiple connections,
    // but here we are testing the logic. For absolute isolation, we'd need real DBs or mock the connection switch).
    
    // Rule: In tests, we will mock the connection switch or use sqlite databases.
});

test('customer can log into their own tenant shop', function () {
    Config::set('app.central_domain', 'localhost');
    
    // Simulate being on Tenant A
    $manager = app(TenantManager::class);
    
    // Manually migrate Tenant A's database
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);

    // Create customer in Tenant A
    $customer = Customer::on('tenant')->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]);

    // Verify we can authenticate as this customer using the 'customer' guard
    Auth::shouldUse('customer');
    
    $success = Auth::once([
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    expect($success)->toBeTrue();
    expect(Auth::guard('customer')->user()->id)->toBe($customer->id);
});

test('customer cannot log into a different tenant shop', function () {
    Config::set('app.central_domain', 'localhost');
    
    // 1. Setup Tenant A and create a customer
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->artisan('migrate', ['--database' => 'tenant', '--path' => 'database/migrations/tenant', '--realpath' => true]);
    
    Customer::on('tenant')->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]);

    // 2. Simulate switching to Tenant B (which has a FRESH in-memory database)
    // In a real app, the SetTenantConnection middleware would do this.
    // Here we just ensure that the 'tenant' connection is wiped/switched.
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    
    \Illuminate\Support\Facades\DB::purge('tenant');
    \Illuminate\Support\Facades\DB::reconnect('tenant');

    $this->artisan('migrate', ['--database' => 'tenant', '--path' => 'database/migrations/tenant', '--realpath' => true]);

    // Try to log in on Tenant B with Tenant A's credentials
    $success = Auth::guard('customer')->once([
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    expect($success)->toBeFalse();
});
