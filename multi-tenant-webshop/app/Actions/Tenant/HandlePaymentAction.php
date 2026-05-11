<?php

namespace App\Actions\Tenant;

use App\Models\Tenant\Order;
use Illuminate\Support\Facades\Log;

class HandlePaymentAction
{
    /**
     * Handle a successful payment for an order.
     */
    public function execute(string $orderId, string $paymentIntentId): void
    {
        $order = Order::findOrFail($orderId);

        if ($order->status === 'paid') {
            Log::info('Order already marked as paid', ['order_id' => $orderId]);
            return;
        }

        $order->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => $paymentIntentId,
        ]);

        Log::info('Order marked as paid via webhook', [
            'order_id' => $orderId,
            'payment_intent_id' => $paymentIntentId
        ]);

        // Future: trigger stock updates, send confirmation emails, etc. (Step 20)
    }
}
