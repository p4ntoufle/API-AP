<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixUrl
{
    public function handle(Request $request, Closure $next)
    {
        // If we're behind a reverse proxy, fix the URL
        if ($request->server('HTTP_X_FORWARDED_PROTO')) {
            $request->server->set('HTTPS', $request->server('HTTP_X_FORWARDED_PROTO') === 'https' ? 'on' : 'off');
        }
        
        if ($request->server('HTTP_X_FORWARDED_HOST')) {
            $request->server->set('HTTP_HOST', $request->server('HTTP_X_FORWARDED_HOST'));
        }

        return $next($request);
    }
}
