<?php

use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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

    // Create a real tenant record to satisfy TenantManager
    $tenant = \App\Models\Landlord\Tenant::create([
        'name' => 'Test Shop',
        'db_name' => ':memory:',
    ]);
    app(\App\Services\TenantManager::class)->setTenant($tenant);

    // Migrate tenant tables
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);
});

test('can create product variations with attributes', function () {
    $category = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'T-Shirt',
        'slug' => 't-shirt',
        'price' => 20.00,
    ]);

    $colorAttr = Attribute::create(['name' => 'Color']);
    $redValue = AttributeValue::create(['attribute_id' => $colorAttr->id, 'value' => 'Red']);
    
    $sizeAttr = Attribute::create(['name' => 'Size']);
    $xlValue = AttributeValue::create(['attribute_id' => $sizeAttr->id, 'value' => 'XL']);

    $variation = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'TSHIRT-RED-XL',
        'price' => 25.00,
        'stock' => 5,
    ]);

    $variation->attributeValues()->attach([$redValue->id, $xlValue->id]);

    expect($product->variations()->count())->toBe(1);
    expect($variation->attributeValues()->count())->toBe(2);
    expect($variation->effective_price)->toBe(25.00);
});

test('variation price falls back to product price if null', function () {
    $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Phone Case',
        'slug' => 'phone-case',
        'price' => 15.00,
    ]);

    $variation = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'CASE-BLUE',
        'price' => null, // No override
        'stock' => 10,
    ]);

    expect($variation->effective_price)->toBe(15.00);
});

test('product can detect if it has variations', function () {
    $category = Category::create(['name' => 'Test', 'slug' => 'test']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Simple Product',
        'slug' => 'simple',
        'price' => 10.00,
    ]);

    expect($product->has_variations)->toBeFalse();

    ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'VAR-1',
        'stock' => 5,
    ]);

    expect($product->fresh()->has_variations)->toBeTrue();
});
