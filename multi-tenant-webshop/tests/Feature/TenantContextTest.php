<?php

use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the tenant helper returns the current tenant', function () {
    $tenant = new Tenant([
        'id' => 'test-id',
        'name' => 'Test Tenant'
    ]);

    $this->mock(TenantManager::class, function ($mock) use ($tenant) {
        $mock->shouldReceive('getTenant')->andReturn($tenant);
    });

    expect(tenant()->id)->toBe($tenant->id);
    expect(is_tenant_context())->toBeTrue();
});

test('the tenant helper returns null when no tenant is set', function () {
    // Ensure we have a clean mock or a real instance that returns null
    $this->mock(TenantManager::class, function ($mock) {
        $mock->shouldReceive('getTenant')->andReturn(null);
    });

    expect(tenant())->toBeNull();
    expect(is_tenant_context())->toBeFalse();
});
