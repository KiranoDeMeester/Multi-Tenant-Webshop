<?php

namespace App\Actions\Tenant;

use App\Mail\OrderPaidMail;
use App\Models\Tenant\Coupon;
use App\Models\Tenant\Order;
use App\Services\StockService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

            // 1. Update Stock atomically and record stock mutation audit entries
            app(StockService::class)->fulfillOrderStock($order);

            // 2. Increment coupon used count if a coupon was used
            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // 3. Send Confirmation Email (passing primitive IDs instead of Model to avoid Queue serialization issues)
            $customerEmail = $order->customer_details['email'] ?? $order->customer?->email;

            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderPaidMail($order->id, $tenantId));
                Log::info('Order confirmation email queued', array_merge($context, ['email' => $customerEmail]));
            } else {
                Log::warning('Could not send order confirmation: No email found', $context);
            }

            Log::info('Order finalized successfully', $context);
        } catch (\Exception $e) {
            Log::error('Failed to finalize order: '.$e->getMessage(), array_merge($context, [
                'exception' => $e,
            ]));
            throw $e; // Rethrow to let calling payment action know it failed
        }
    }
}
