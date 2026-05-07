<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Domain;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantConnection
{
    public function __construct(
        protected TenantManager $tenantManager
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomain = config('app.central_domain', env('CENTRAL_DOMAIN', 'localhost'));

        // If we are on the central domain, keep the landlord connection
        $allowedCentralHosts = [$centralDomain, 'platform.' . $centralDomain, 'localhost', '127.0.0.1'];
        if (in_array($host, $allowedCentralHosts)) {
            return $next($request);
        }

        // Try to find the tenant by domain
        $domain = \App\Models\Landlord\Domain::where('domain', $host)->with('tenant')->first();

        if (!$domain || !$domain->tenant) {
            abort(404, 'Webshop niet gevonden.');
        }

        if (empty($domain->tenant->db_name)) {
            abort(500, 'Interne configuratiefout: Webshop database niet ingesteld.');
        }

        // Switch the connection
        $this->tenantManager->setTenant($domain->tenant);

        return $next($request);
    }
}
