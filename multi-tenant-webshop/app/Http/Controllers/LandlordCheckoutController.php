<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;

class LandlordCheckoutController extends Controller
{
    public function __construct(protected StripeService $stripeService) {}

    /**
     * Redirect to Stripe Checkout for landlord subscription.
     */
    public function subscribe(Request $request)
    {
        // Determine the redirect URLs
        // Note: Stripe requires absolute URLs
        $port = request()->getPort();
        $domain = config('app.central_domain', 'localhost');
        if ($port && ! in_array($port, [80, 443])) {
            $domain = "{$domain}:{$port}";
        }
        $protocol = str_contains(config('app.url'), 'https') ? 'https' : 'http';

        $successUrl = "{$protocol}://{$domain}/onboarding?session_id={CHECKOUT_SESSION_ID}";
        $cancelUrl = "{$protocol}://{$domain}/?cancelled=1";

        try {
            $session = $this->stripeService->createLandlordSubscriptionSession($successUrl, $cancelUrl);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Kon betaling niet starten: '.$e->getMessage());
        }
    }
}
