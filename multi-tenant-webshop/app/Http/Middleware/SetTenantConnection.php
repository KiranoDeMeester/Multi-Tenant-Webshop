<?php

namespace App\Http\Middleware;

use App\Models\Landlord\Domain;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
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
        $allowedCentralHosts = [$centralDomain, 'platform.'.$centralDomain, 'localhost', '127.0.0.1'];
        if (in_array($host, $allowedCentralHosts)) {
            Config::set('database.default', env('DB_CONNECTION', 'landlord'));
            DB::purge('tenant');

            return $next($request);
        }

        // Try to find the tenant by domain (cached for performance)
        $domain = Cache::remember("tenant_domain:{$host}", 3600, function () use ($host) {
            return Domain::where('domain', $host)->with('tenant')->first();
        });

        if (! $domain || ! $domain->tenant) {
            abort(404, 'Webshop niet gevonden.');
        }

        if (! $domain->tenant->is_active) {
            abort(403, 'Deze webshop is momenteel niet beschikbaar of gedeactiveerd.');
        }

        if (empty($domain->tenant->db_name)) {
            abort(500, 'Interne configuratiefout: Webshop database niet ingesteld.');
        }

        // 3. Set Tenant Context
        $tenantManager = app(TenantManager::class);
        $tenantManager->setTenant($domain->tenant);

        // 4. Set Global Route Default for 'tenant' parameter
        URL::defaults([
            'tenant' => $domain->tenant->slug,
        ]);

        return $next($request);
    }
}
