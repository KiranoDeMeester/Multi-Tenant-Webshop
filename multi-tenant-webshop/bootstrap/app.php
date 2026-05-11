<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            \App\Http\Middleware\SetTenantConnection::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        $middleware->alias([
            'central' => \App\Http\Middleware\EnsureCentralDomain::class,
            'tenant' => \App\Http\Middleware\SetTenantConnection::class,
        ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $host = $request->getHost();
            $centralDomain = config('app.central_domain', 'localhost');
            $allowedCentralHosts = [$centralDomain, 'platform.' . $centralDomain, 'localhost', '127.0.0.1'];

            if (!in_array($host, $allowedCentralHosts)) {
                // Extract tenant subdomain from host (e.g. demo-shop.localhost -> demo-shop)
                $tenant = \Illuminate\Support\Str::before($host, '.');
                return route('tenant.login', ['tenant' => $tenant]);
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
