<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomain = config('app.central_domain', 'localhost');

        // Allow access only if it's the central domain, platform subdomain, or localhost/127.0.0.1
        $allowedHosts = [$centralDomain, 'platform.'.$centralDomain, 'localhost', '127.0.0.1'];

        if (! in_array($host, $allowedHosts)) {
            abort(404, 'Platform admin is niet toegankelijk vanaf tenant domeinen.');
        }

        return $next($request);
    }
}
