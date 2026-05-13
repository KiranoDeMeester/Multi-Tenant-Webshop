<?php

use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

// uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup tenant connection to use sqlite in-memory
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    DB::purge('tenant');
    DB::reconnect('tenant');

    // Migrate tenant tables
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);
});

test('can create a category with uuid', function () {
    $category = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
        'description' => 'Gadgets and more',
    ]);

    expect($category->id)->toBeString();
    expect(strlen($category->id))->toBe(36); // UUID length
    expect($category->name)->toBe('Electronics');
});

test('can create a product linked to a category', function () {
    $category = Category::create([
        'name' => 'Laptops',
        'slug' => 'laptops',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'MacBook Pro',
        'slug' => 'macbook-pro',
        'sku' => 'MBP-001',
        'price' => 1999.99,
        'stock' => 10,
    ]);

    expect($product->category_id)->toBe($category->id);
    expect($product->category->name)->toBe('Laptops');
    expect($category->products->count())->toBe(1);
});

test('soft deletes work for products', function () {
    $category = Category::create(['name' => 'Test', 'slug' => 'test']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Delete Me',
        'slug' => 'delete-me',
        'sku' => 'DEL-001',
        'price' => 10.00,
    ]);

    $product->delete();

    expect(Product::count())->toBe(0);
    expect(Product::withTrashed()->count())->toBe(1);
    expect($product->deleted_at)->not->toBeNull();
});
