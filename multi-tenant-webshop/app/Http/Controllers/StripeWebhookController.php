<?php

namespace App\Http\Controllers;

use App\Actions\Tenant\HandlePaymentAction;
use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhooks.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Ongeldige payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Ongeldige handtekening'], 400);
        }

        Log::info('Stripe Webhook ontvangen: ' . $event->type);

        // Handle specific events
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->processCheckoutSession($session);
                break;
                
            case 'checkout.session.async_payment_succeeded':
                $session = $event->data->object;
                $this->processCheckoutSession($session);
                break;

            default:
                Log::debug('Onbehandeld Stripe event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Process a completed checkout session.
     */
    protected function processCheckoutSession($session)
    {
        $tenantId = $session->metadata->tenant_id ?? null;
        $orderId = $session->metadata->order_id ?? null;

        if (!$tenantId || !$orderId) {
            Log::error('Stripe Webhook Fout: Ontbrekende metadata in sessie', [
                'session_id' => $session->id,
                'metadata' => $session->metadata
            ]);
            return;
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            Log::error('Stripe Webhook Fout: Tenant niet gevonden', [
                'tenant_id' => $tenantId
            ]);
            return;
        }

        try {
            // 1. Switch naar de juiste tenant database context
            app(TenantManager::class)->setTenant($tenant);

            // Extract customer details for snapshots
            $customerDetails = [
                'email' => $session->customer_details->email ?? null,
                'name' => $session->customer_details->name ?? null,
                'address' => $session->customer_details->address ?? null,
            ];

            // 2. Voer de betalings-afhandeling uit binnen die context
            app(HandlePaymentAction::class)->execute(
                $orderId, 
                $session->payment_intent ?? 'n/a',
                $customerDetails
            );

            Log::info("Stripe Webhook succesvol verwerkt voor tenant {$tenant->name}", [
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error("Fout bij verwerken Stripe Webhook voor tenant {$tenant->name}: " . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e
            ]);
        }
    }
}
