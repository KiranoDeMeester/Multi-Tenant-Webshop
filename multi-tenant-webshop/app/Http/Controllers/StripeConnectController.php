<?php

namespace App\Http\Controllers;

use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\OAuth;
use Illuminate\Support\Facades\Log;

class StripeConnectController extends Controller
{
    /**
     * Redirect the tenant to Stripe OAuth.
     */
    public function redirect()
    {
        $tenant = app(TenantManager::class)->getTenant();
        
        if (!$tenant) {
            return redirect()->back()->with('error', 'Kon webshop niet identificeren.');
        }

        // We use a state that includes the tenant ID to identify them on the callback
        // Since callback is on the central domain, we might lose the session.
        $state = bin2hex(random_bytes(16)) . ':' . $tenant->id;
        
        // In a production app, you should store this state in the session or cache 
        // and verify it in the callback to prevent CSRF.
        // For this implementation, we rely on the tenant ID in the state.
        
        $authorizeUrl = OAuth::authorizeUrl([
            'client_id' => config('services.stripe.client_id'),
            'scope' => 'read_write',
            'state' => $state,
            'redirect_uri' => route('stripe.callback', [], true),
        ]);

        return redirect($authorizeUrl);
    }

    /**
     * Handle the callback from Stripe.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            Log::error("Stripe OAuth Error: " . $request->error_description);
            return redirect()->route('home')->with('error', 'Stripe error: ' . $request->error_description);
        }

        if (!$request->has('code')) {
            return redirect()->route('home')->with('error', 'Geen autorisatiecode ontvangen.');
        }

        $state = $request->state;
        if (!$state || !str_contains($state, ':')) {
            abort(403, 'Ongeldige state parameter.');
        }

        [, $tenantId] = explode(':', $state);

        $tenant = Tenant::findOrFail($tenantId);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $response = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code,
            ]);

            $tenant->update([
                'stripe_account_id' => $response->stripe_user_id,
            ]);

            Log::info("Tenant connected to Stripe: {$tenant->name} ({$response->stripe_user_id})");

            // Build redirect URL back to tenant dashboard
            $domain = $tenant->domains()->first()?->domain;
            $port = request()->getPort();
            if ($port && !in_array($port, [80, 443])) {
                $domain = "{$domain}:{$port}";
            }
            $protocol = str_contains(config('app.url'), 'https') ? 'https' : 'http';
            
            return redirect("{$protocol}://{$domain}/dashboard/payments")->with('success', 'Stripe account succesvol verbonden!');
        } catch (\Exception $e) {
            Log::error("Stripe Connect Callback Error: " . $e->getMessage());
            return redirect()->route('home')->with('error', 'Kon niet verbinden met Stripe: ' . $e->getMessage());
        }
    }
}
