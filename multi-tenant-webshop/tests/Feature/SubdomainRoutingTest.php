<?php

use App\Models\Landlord\Tenant;
use App\Models\Landlord\Domain;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->migrateLandlord();
});

test('the platform admin is accessible from platform.localhost', function () {
    Config::set('app.central_domain', 'localhost');

    $response = $this->get('http://platform.localhost/');

    $response->assertStatus(200);
});

test('the platform admin is not accessible from a tenant subdomain', function () {
    Config::set('app.central_domain', 'localhost');

    $response = $this->get('http://tenant1.localhost/admin/tenants');

    $response->assertStatus(404);
});

test('the tenant webshop is accessible from a tenant subdomain', function () {
    Config::set('app.central_domain', 'localhost');

    $subdomain = 'tenant' . strtolower(Str::random(8));
    $fullDomain = $subdomain . '.localhost';

    // Create a tenant and a domain record
    $tenant = Tenant::factory()->create(['db_name' => ':memory:']);
    Domain::create([
        'domain' => $fullDomain,
        'tenant_id' => $tenant->id,
        'is_primary' => true,
    ]);

    // 2. Use a file-based database so it persists during the request
    $dbPath = database_path('tenants/test_routing.sqlite');
    if (!file_exists(dirname($dbPath))) mkdir(dirname($dbPath), 0755, true);
    touch($dbPath);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', $dbPath);
    $this->migrateTenant();
    
    // Ensure the tenant record points to this file
    $tenant->update(['db_name' => 'test_routing']);

    $response = $this->get('http://' . $fullDomain . '/');

    $response->assertStatus(200);
    $response->assertSee($tenant->name);

    // Cleanup
    if (file_exists($dbPath)) {
        \Illuminate\Support\Facades\DB::purge('tenant');
        unlink($dbPath);
    }
});
