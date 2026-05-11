<?php

namespace App\Actions\Tenant;

use App\Mail\OrderPaidMail;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FinalizeOrderAction
{
    /**
     * Finalize an order by updating stock and notifying the customer.
     */
    public function execute(Order $order): void
    {
        try {
            Log::info('Finalizing order', ['order_id' => $order->id]);

            // 1. Update Stock
            foreach ($order->items as $item) {
                try {
                    if ($item->product_variation_id) {
                        $variation = ProductVariation::find($item->product_variation_id);
                        if ($variation) {
                            $variation->decrementStock($item->quantity);
                        }
                    } elseif ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->decrementStock($item->quantity);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Stock decrement failed for order item {$item->id}: " . $e->getMessage());
                    // We continue even if stock update fails for one item (best effort)
                }
            }

            // 2. Send Confirmation Email
            $customerEmail = $order->customer_details['email'] ?? $order->customer?->email;

            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderPaidMail($order->load('items')));
                Log::info('Order confirmation email sent', ['order_id' => $order->id, 'email' => $customerEmail]);
            } else {
                Log::warning('Could not send order confirmation: No email found', ['order_id' => $order->id]);
            }

            Log::info('Order finalized successfully', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Failed to finalize order: ' . $e->getMessage(), ['order_id' => $order->id]);
        }
    }
}
