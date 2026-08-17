<?php

use App\Http\Middleware\SetTenantConnection;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Cache Test Store',
        'slug' => 'cachestore',
        'db_name' => 'tenant_cachestore',
    ]);

    $this->domain = Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'cachestore.localhost',
        'is_primary' => true,
    ]);
});

test('set tenant connection middleware caches domain lookups in memory cache', function () {
    Cache::flush();

    $middleware = new SetTenantConnection(app(TenantManager::class));
    $request = Request::create('http://cachestore.localhost/test', 'GET');

    expect(Cache::has('tenant_domain:cachestore.localhost'))->toBeFalse();

    // First request - populates cache
    $middleware->handle($request, function ($req) {
        return response('OK');
    });

    expect(Cache::has('tenant_domain:cachestore.localhost'))->toBeTrue();
    $cached = Cache::get('tenant_domain:cachestore.localhost');
    expect($cached->id)->toBe($this->domain->id);
    expect($cached->tenant->slug)->toBe('cachestore');
});
