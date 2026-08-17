<?php

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerAddress;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\User as TenantUser;
use App\Models\User as LandlordUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->migrateLandlord();

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();
});

test('tenant admin can create, update, and delete products', function () {
    $tenantUser = TenantUser::create([
        'name' => 'Store Owner',
        'email' => 'owner@testshop.localhost',
        'password' => bcrypt('password'),
    ]);

    $category = \App\Models\Tenant\Category::create([
        'name' => 'General',
        'slug' => 'general',
    ]);

    $product = Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'PROD-001',
        'price' => 19.99,
        'stock' => 10,
        'category_id' => $category->id,
    ]);

    expect(Gate::forUser($tenantUser)->allows('create', Product::class))->toBeTrue();
    expect(Gate::forUser($tenantUser)->allows('update', $product))->toBeTrue();
    expect(Gate::forUser($tenantUser)->allows('delete', $product))->toBeTrue();
});

test('customer cannot manage products but can view them', function () {
    $customer = Customer::create([
        'name' => 'Customer Bob',
        'email' => 'bob@example.com',
        'password' => bcrypt('password'),
    ]);

    $category = \App\Models\Tenant\Category::create([
        'name' => 'General',
        'slug' => 'general',
    ]);

    $product = Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'PROD-001',
        'price' => 19.99,
        'stock' => 10,
        'category_id' => $category->id,
    ]);

    expect(Gate::forUser($customer)->allows('view', $product))->toBeTrue();
    expect(Gate::forUser($customer)->allows('create', Product::class))->toBeFalse();
    expect(Gate::forUser($customer)->allows('update', $product))->toBeFalse();
    expect(Gate::forUser($customer)->allows('delete', $product))->toBeFalse();
});

test('customer cannot view or cancel another customer order', function () {
    $customer1 = Customer::create([
        'name' => 'Customer One',
        'email' => 'one@example.com',
        'password' => bcrypt('password'),
    ]);

    $customer2 = Customer::create([
        'name' => 'Customer Two',
        'email' => 'two@example.com',
        'password' => bcrypt('password'),
    ]);

    $order = Order::create([
        'order_number' => 'ORD-TEST-001',
        'total_amount' => 5000,
        'status' => 'paid',
        'customer_id' => $customer1->id,
    ]);

    expect(Gate::forUser($customer1)->allows('view', $order))->toBeTrue();
    expect(Gate::forUser($customer1)->allows('cancel', $order))->toBeTrue();

    expect(Gate::forUser($customer2)->allows('view', $order))->toBeFalse();
    expect(Gate::forUser($customer2)->allows('cancel', $order))->toBeFalse();
});

test('customer cannot modify or delete another customer address (IDOR protection)', function () {
    $customer1 = Customer::create([
        'name' => 'Customer One',
        'email' => 'one@example.com',
        'password' => bcrypt('password'),
    ]);

    $customer2 = Customer::create([
        'name' => 'Customer Two',
        'email' => 'two@example.com',
        'password' => bcrypt('password'),
    ]);

    $address = CustomerAddress::create([
        'customer_id' => $customer1->id,
        'type' => 'shipping',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'street' => 'Main St',
        'house_number' => '12',
        'postal_code' => '1000',
        'city' => 'Brussels',
        'country' => 'België',
    ]);

    expect(Gate::forUser($customer1)->allows('update', $address))->toBeTrue();
    expect(Gate::forUser($customer1)->allows('delete', $address))->toBeTrue();

    expect(Gate::forUser($customer2)->allows('update', $address))->toBeFalse();
    expect(Gate::forUser($customer2)->allows('delete', $address))->toBeFalse();
});

test('landlord admin can manage platform tenants', function () {
    $admin = LandlordUser::create([
        'name' => 'Platform Admin',
        'email' => 'admin@platform.localhost',
        'password' => bcrypt('password'),
    ]);

    $tenant = Tenant::create([
        'name' => 'Sample Shop',
        'db_name' => 'sample_shop_db',
    ]);

    expect(Gate::forUser($admin)->allows('viewAny', Tenant::class))->toBeTrue();
    expect(Gate::forUser($admin)->allows('create', Tenant::class))->toBeTrue();
    expect(Gate::forUser($admin)->allows('update', $tenant))->toBeTrue();
    expect(Gate::forUser($admin)->allows('delete', $tenant))->toBeTrue();
});
