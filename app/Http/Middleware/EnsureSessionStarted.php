<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession as StartSessionMiddleware;

class EnsureSessionStarted extends StartSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = parent::handle($request, $next);
        
        // Force a session item to ensure the session is saved
        if (!session()->has('_started')) {
            session()->put('_started', true);
        }
        
        return $response;
    }
}
