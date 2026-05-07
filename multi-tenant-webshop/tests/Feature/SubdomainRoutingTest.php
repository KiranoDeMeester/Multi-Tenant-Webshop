<?php

use App\Models\Landlord\Tenant;
use App\Models\Landlord\Domain;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

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
    $tenant = Tenant::factory()->create();
    Domain::create([
        'domain' => $fullDomain,
        'tenant_id' => $tenant->id,
        'is_primary' => true,
    ]);

    // Mock TenantManager
    $this->mock(TenantManager::class, function ($mock) {
        $mock->shouldReceive('setTenant')->andReturnNull();
    });

    $response = $this->get('http://' . $fullDomain . '/');

    $response->assertStatus(200);
    $response->assertSee('Welcome to webshop: ' . $subdomain);
});
