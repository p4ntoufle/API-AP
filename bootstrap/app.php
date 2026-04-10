<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust proxies for reverse proxy scenarios
        $proxies = env('TRUSTED_PROXIES', '127.0.0.1');
        $headers = env('TRUSTED_HEADERS', 
            \Illuminate\Http\Middleware\TrustProxies::HEADER_X_FORWARDED_FOR |
            \Illuminate\Http\Middleware\TrustProxies::HEADER_X_FORWARDED_HOST |
            \Illuminate\Http\Middleware\TrustProxies::HEADER_X_FORWARDED_PROTO
        );
        
        $middleware->trustProxies(
            at: explode(',', $proxies),
            headers: $headers
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
