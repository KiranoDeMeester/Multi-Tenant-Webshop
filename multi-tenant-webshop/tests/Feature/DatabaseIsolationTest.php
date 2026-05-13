<?php

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use App\Services\TenantManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->migrateLandlord();
    
    $this->testId = uniqid();
    $this->testDir = 'testing_' . $this->testId;
    
    // Create testing directory for tenant databases
    if (!File::exists(database_path('tenants/' . $this->testDir))) {
        File::makeDirectory(database_path('tenants/' . $this->testDir), 0755, true);
    }
});

afterEach(function () {
    // Clean up testing databases
    File::deleteDirectory(database_path('tenants/' . $this->testDir));
});

test('data is strictly isolated between tenants', function () {
    // 1. Create two tenants
    $tenantA = Tenant::create([
        'name' => 'Tenant A',
        'db_name' => $this->testDir . '/tenant_a',
    ]);
    
    $tenantB = Tenant::create([
        'name' => 'Tenant B',
        'db_name' => $this->testDir . '/tenant_b',
    ]);

    $manager = app(TenantManager::class);

    // 2. Switch to Tenant A and create data
    $manager->setTenant($tenantA);
    $this->migrateTenant(); // Migrate the new file
    
    $categoryA = Category::create(['name' => 'Cat A', 'slug' => 'cat-a']);
    Product::create([
        'name' => 'Product A',
        'slug' => 'product-a',
        'sku' => 'SKU-A',
        'price' => 10,
        'category_id' => $categoryA->id,
    ]);

    expect(Product::count())->toBe(1);
    expect(Product::first()->name)->toBe('Product A');

    // 3. Switch to Tenant B and verify it's empty, then create data
    $manager->setTenant($tenantB);
    $this->migrateTenant(); // Migrate the other new file
    
    expect(Product::count())->toBe(0); // SHOULD BE EMPTY

    $categoryB = Category::create(['name' => 'Cat B', 'slug' => 'cat-b']);
    Product::create([
        'name' => 'Product B',
        'slug' => 'product-b',
        'sku' => 'SKU-B',
        'price' => 20,
        'category_id' => $categoryB->id,
    ]);

    expect(Product::count())->toBe(1);
    expect(Product::first()->name)->toBe('Product B');

    // 4. Switch back to Tenant A and verify original data is still there
    $manager->setTenant($tenantA);
    
    expect(Product::count())->toBe(1);
    expect(Product::first()->name)->toBe('Product A');
    expect(Product::where('name', 'Product B')->exists())->toBeFalse();
});
