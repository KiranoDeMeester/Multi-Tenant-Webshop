<?php

namespace App\Actions\Tenant;

use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Services\CartService;
use App\Services\StripeService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PrepareCheckoutAction
{
    public function __construct(
        protected CartService $cartService,
        protected StripeService $stripeService,
        protected TenantManager $tenantManager
    ) {}

    /**
     * Prepare the checkout by creating a local order and a Stripe session.
     * 
     * @return string The Stripe Checkout URL
     * @throws \Exception
     */
    public function execute(): string
    {
        $tenant = $this->tenantManager->getTenant();
        
        if (!$tenant || !$tenant->stripe_account_id) {
             throw new \Exception('Deze winkel kan momenteel geen betalingen accepteren (Stripe niet geconfigureerd).');
        }

        $items = $this->cartService->getItems();
        if (empty($items)) {
            throw new \Exception('Winkelwagen is leeg.');
        }

        try {
            return DB::transaction(function () use ($tenant, $items) {
                $total = (int) round($this->cartService->getTotal() * 100);

                // 1. Create Order
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'total_amount' => $total,
                    'status' => 'pending',
                    'customer_id' => auth('customer')->id(),
                ]);

                // 2. Create Order Items (Snapshots)
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_variation_id' => $item['variation_id'] ?? null,
                        'product_name' => $item['name'],
                        'sku' => $item['sku'] ?? null,
                        'price' => (int) round($item['price'] * 100),
                        'quantity' => $item['quantity'],
                    ]);
                }

                // 3. Create Stripe Session via Service
                $session = $this->stripeService->createCheckoutSession($order, $tenant->stripe_account_id);

                // 4. Update order with session ID
                $order->update([
                    'stripe_session_id' => $session->id,
                ]);

                Log::info('Checkout session prepared', [
                    'order_id' => $order->id,
                    'stripe_session_id' => $session->id,
                    'tenant_id' => $tenant->id
                ]);

                return $session->url;
            });
        } catch (\Exception $e) {
            Log::error('PrepareCheckoutAction failed: ' . $e->getMessage(), [
                'tenant_id' => $tenant->id,
                'user_id' => auth()->id()
            ]);
            throw $e;
        }
    }
}
