<?php

use App\Livewire\Tenant\Products\StockHistory;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Product;
use App\Models\Tenant\StockMutation;
use App\Models\Tenant\User as TenantUser;
use App\Services\StockService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Tech Boutique',
        'slug' => 'techboutique',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'techboutique.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();

    $this->merchant = TenantUser::create([
        'name' => 'Tech Merchant',
        'email' => 'merchant@tech.localhost',
        'password' => bcrypt('password'),
    ]);

    $this->category = Category::create([
        'name' => 'Gadgets',
        'slug' => 'gadgets',
    ]);
});

test('stock service adjusts stock and creates mutation records', function () {
    $product = Product::create([
        'name' => 'Smart Watch',
        'slug' => 'smart-watch',
        'sku' => 'WATCH-01',
        'price' => 199.99,
        'stock' => 10,
        'category_id' => $this->category->id,
    ]);

    $stockService = app(StockService::class);

    // Increase stock (purchase)
    $mutation1 = $stockService->adjustStock($product, null, 15, 'purchase', null, 'Inkoop nieuwe voorraad');

    expect($product->fresh()->stock)->toBe(25);
    expect($mutation1->stock_before)->toBe(10);
    expect($mutation1->stock_after)->toBe(25);
    expect($mutation1->type)->toBe('purchase');

    // Decrease stock (sale)
    $mutation2 = $stockService->adjustStock($product, null, -5, 'sale', null, 'Handmatige verkoop');

    expect($product->fresh()->stock)->toBe(20);
    expect($mutation2->stock_before)->toBe(25);
    expect($mutation2->stock_after)->toBe(20);
});

test('order fulfillment deducts stock and order cancellation restitutes stock', function () {
    $product = Product::create([
        'name' => 'Noise Cancelling Headphones',
        'slug' => 'headphones',
        'sku' => 'HEAD-01',
        'price' => 150.00,
        'stock' => 8,
        'category_id' => $this->category->id,
    ]);

    $customer = Customer::create([
        'name' => 'Alice Tech',
        'email' => 'alice@tech.org',
        'password' => bcrypt('password'),
    ]);

    $order = Order::create([
        'order_number' => 'ORD-TECH-999',
        'total_amount' => 30000,
        'status' => 'pending',
        'customer_id' => $customer->id,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 15000,
        'quantity' => 2,
    ]);

    $stockService = app(StockService::class);

    // 1. Fulfill
    $stockService->fulfillOrderStock($order);
    expect($product->fresh()->stock)->toBe(6);
    expect(StockMutation::where('order_id', $order->id)->where('type', 'sale')->count())->toBe(1);

    // 2. Cancel and Restitute
    $stockService->restituteOrderStock($order);
    expect($product->fresh()->stock)->toBe(8);
    expect(StockMutation::where('order_id', $order->id)->where('type', 'cancel_restitution')->count())->toBe(1);
});

test('merchant can view stock history and perform manual adjustment via livewire component', function () {
    Auth::guard('tenant')->login($this->merchant);

    $product = Product::create([
        'name' => 'Drone Quadcopter',
        'slug' => 'drone',
        'sku' => 'DRONE-01',
        'price' => 499.00,
        'stock' => 5,
        'category_id' => $this->category->id,
    ]);

    Livewire::test(StockHistory::class)
        ->assertSee('Drone Quadcopter')
        ->set('adjustProductId', $product->id)
        ->set('adjustDelta', 10)
        ->set('adjustType', 'purchase')
        ->set('adjustReason', 'Levering leverancier')
        ->call('saveAdjustment')
        ->assertHasNoErrors();

    expect($product->fresh()->stock)->toBe(15);
    expect(StockMutation::where('product_id', $product->id)->count())->toBe(1);
});
