<?php

namespace App\Services;

use App\Models\Tenant\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout session with Destination Charges.
     */
    public function createCheckoutSession(Order $order, string $connectedAccountId)
    {
        try {
            $platformFeePercentage = config('services.stripe.platform_fee', 5);
            $applicationFeeAmount = (int) round($order->total_amount * ($platformFeePercentage / 100));

            $lineItems = $order->items->map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product_name,
                            'metadata' => [
                                'sku' => $item->sku,
                            ],
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => $item->quantity,
                ];
            })->toArray();

            // Add shipping as a line item if it exists
            if ($order->shipping_amount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => __('Verzendkosten'),
                        ],
                        'unit_amount' => $order->shipping_amount,
                    ],
                    'quantity' => 1,
                ];
            }

            return Session::create([
                'payment_method_types' => ['card', 'ideal'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('storefront.checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('storefront.checkout.cancel', [], true),
                'payment_intent_data' => [
                    'application_fee_amount' => $applicationFeeAmount,
                    'transfer_data' => [
                        'destination' => $connectedAccountId,
                    ],
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'tenant_id' => app(TenantManager::class)->getTenant()->id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Session Creation Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'tenant_id' => app(TenantManager::class)->getTenant()->id,
            ]);
            throw $e;
        }
    }
}
