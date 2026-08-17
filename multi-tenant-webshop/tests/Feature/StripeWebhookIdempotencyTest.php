<?php

use App\Actions\Tenant\HandlePaymentAction;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Product;
use App\Models\Tenant\StockMutation;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Boutique Store',
        'slug' => 'boutiquestore',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'boutiquestore.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();

    $this->category = Category::create([
        'name' => 'Kleding',
        'slug' => 'kleding',
    ]);

    $this->customer = Customer::create([
        'name' => 'David Tennant',
        'email' => 'david@bbc.co.uk',
        'password' => bcrypt('password'),
    ]);

    $this->product = Product::create([
        'name' => 'Trench Coat',
        'slug' => 'trench-coat',
        'sku' => 'COAT-10',
        'price' => 200.00,
        'stock' => 10,
        'category_id' => $this->category->id,
    ]);
});

test('handle payment action is idempotent and handles duplicate webhook executions gracefully', function () {
    Mail::fake();

    $order = Order::create([
        'order_number' => 'ORD-HOOK-001',
        'total_amount' => 20000,
        'status' => 'pending',
        'customer_id' => $this->customer->id,
        'customer_details' => [
            'name' => 'David Tennant',
            'email' => 'david@bbc.co.uk',
        ]
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => 'Trench Coat',
        'sku' => 'COAT-10',
        'price' => 20000,
        'quantity' => 2,
    ]);

    $handlePayment = app(HandlePaymentAction::class);

    // 1st Webhook Execution
    $handlePayment->execute($order->id, 'pi_test_12345', ['email' => 'david@bbc.co.uk']);

    expect($order->fresh()->status)->toBe('paid');
    expect($this->product->fresh()->stock)->toBe(8);
    expect(StockMutation::where('order_id', $order->id)->where('type', 'sale')->count())->toBe(1);

    // 2nd Webhook Execution (Simulate duplicate retry)
    $handlePayment->execute($order->id, 'pi_test_12345', ['email' => 'david@bbc.co.uk']);

    // Ensure stock wasn't deducted twice and no extra mutation was created
    expect($order->fresh()->status)->toBe('paid');
    expect($this->product->fresh()->stock)->toBe(8);
    expect(StockMutation::where('order_id', $order->id)->where('type', 'sale')->count())->toBe(1);
});
