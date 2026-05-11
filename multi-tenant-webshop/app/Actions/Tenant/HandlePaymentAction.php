<?php

namespace App\Actions\Tenant;

use App\Models\Tenant\Order;
use Illuminate\Support\Facades\Log;

class HandlePaymentAction
{
    /**
     * Handle a successful payment for an order.
     */
    public function execute(string $orderId, string $paymentIntentId, ?array $customerDetails = null): void
    {
        $order = Order::findOrFail($orderId);

        if ($order->status === 'paid') {
            Log::info('Order already marked as paid', ['order_id' => $orderId]);
            return;
        }

        $order->update([
            'status' => 'paid',
            'stripe_payment_intent_id' => $paymentIntentId,
            'customer_details' => $customerDetails ?? $order->customer_details,
        ]);

        Log::info('Order marked as paid via webhook', [
            'order_id' => $orderId,
            'payment_intent_id' => $paymentIntentId
        ]);

        // Finalize order logic (Stock decrement, Email)
        app(FinalizeOrderAction::class)->execute($order);
    }
}
