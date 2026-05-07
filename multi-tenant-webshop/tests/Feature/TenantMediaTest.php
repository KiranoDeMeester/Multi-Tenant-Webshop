<?php

use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup tenant connection to use sqlite in-memory
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    DB::purge('tenant');
    DB::reconnect('tenant');

    // Migrate landlord tables
    $this->artisan('migrate', [
        '--path' => 'database/migrations/landlord',
        '--realpath' => true,
    ]);

    Storage::fake('public');
});

test('product image is stored in tenant specific folder', function () {
    // 1. Create a real tenant record in landlord to set the context
    $tenant = \App\Models\Landlord\Tenant::create([
        'name' => 'Test Shop',
        'db_name' => ':memory:',
    ]);
    app(\App\Services\TenantManager::class)->setTenant($tenant);

    // 2. Migrate tenant tables (including media)
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);

    // 3. Create data AFTER migration
    $category = Category::create(['name' => 'Test', 'slug' => 'test']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Test Product',
        'slug' => 'test-product',
        'price' => 100,
    ]);

    $file = UploadedFile::fake()->image('product.jpg');

    $product->addMedia($file)->toMediaCollection('images');

    $media = $product->getFirstMedia('images');

    // Verify the path contains the tenant ID
    expect($media->getPath())->toContain("tenants/{$tenant->id}/media/{$media->id}");
    
    Storage::disk('public')->assertExists("tenants/{$tenant->id}/media/{$media->id}/product.jpg");
});

test('intervention image conversions are generated', function () {
    // 1. Create a real tenant record in landlord to set the context
    $tenant = \App\Models\Landlord\Tenant::create([
        'name' => 'Test Shop 2',
        'db_name' => ':memory:',
    ]);
    app(\App\Services\TenantManager::class)->setTenant($tenant);

    // 2. Migrate tenant tables (including media)
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);

    // 3. Create data AFTER migration
    $category = Category::create(['name' => 'Test', 'slug' => 'test']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Test Product',
        'slug' => 'test-product',
        'price' => 100,
    ]);

    $file = UploadedFile::fake()->image('product.jpg', 1000, 1000);

    $product->addMedia($file)->toMediaCollection('images');

    $media = $product->getFirstMedia('images');

    // Verify thumb exists
    Storage::disk('public')->assertExists("tenants/{$tenant->id}/media/{$media->id}/conversions/product-thumb.jpg");
});
