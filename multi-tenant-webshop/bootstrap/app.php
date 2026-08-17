<?php

use App\Http\Middleware\EnsureCentralDomain;
use App\Http\Middleware\SetTenantConnection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            SetTenantConnection::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        $middleware->alias([
            'central' => EnsureCentralDomain::class,
            'tenant' => SetTenantConnection::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            $host = $request->getHost();
            $centralDomain = config('app.central_domain', 'localhost');
            $allowedCentralHosts = [$centralDomain, 'platform.'.$centralDomain, 'localhost', '127.0.0.1'];

            if (! in_array($host, $allowedCentralHosts)) {
                // Extract tenant subdomain from host (e.g. demo-shop.localhost -> demo-shop)
                $tenant = Str::before($host, '.');

                return route('tenant.login', ['tenant' => $tenant]);
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
