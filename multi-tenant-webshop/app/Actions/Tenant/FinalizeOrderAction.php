<?php

namespace App\Actions\Tenant;

use App\Mail\OrderPaidMail;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FinalizeOrderAction
{
    /**
     * Finalize an order by updating stock and notifying the customer.
     */
    public function execute(Order $order): void
    {
        $tenant = app(TenantManager::class)->getTenant();
        $tenantId = $tenant?->id;
        $userId = auth('tenant')->id() ?? auth('customer')->id() ?? auth()->id();

        $context = [
            'order_id' => $order->id,
            'tenant_id' => $tenantId,
            'user_id' => $userId,
        ];

        try {
            Log::info('Finalizing order', $context);

            // 1. Update Stock atomically using Transactions and Locks
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    if ($item->product_variation_id) {
                        $variation = ProductVariation::where('id', $item->product_variation_id)
                            ->lockForUpdate()
                            ->first();
                        
                        if ($variation) {
                            $variation->decrementStock($item->quantity);
                        } else {
                            throw new \Exception("Productvariatie niet gevonden: {$item->product_variation_id}");
                        }
                    } elseif ($item->product_id) {
                        $product = Product::where('id', $item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($product) {
                            $product->decrementStock($item->quantity);
                        } else {
                            throw new \Exception("Product niet gevonden: {$item->product_id}");
                        }
                    }
                }
            });

            // 2. Send Confirmation Email (passing primitive IDs instead of Model to avoid Queue serialization issues)
            $customerEmail = $order->customer_details['email'] ?? $order->customer?->email;

            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderPaidMail($order->id, $tenantId));
                Log::info('Order confirmation email queued', array_merge($context, ['email' => $customerEmail]));
            } else {
                Log::warning('Could not send order confirmation: No email found', $context);
            }

            Log::info('Order finalized successfully', $context);
        } catch (\Exception $e) {
            Log::error('Failed to finalize order: ' . $e->getMessage(), array_merge($context, [
                'exception' => $e
            ]));
            throw $e; // Rethrow to let calling payment action know it failed
        }
    }
}

