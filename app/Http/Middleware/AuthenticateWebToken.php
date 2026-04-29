<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WebToken;

class AuthenticateWebToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si un token Bearer est fourni
        $token = $request->bearerToken();
        
        if ($token) {
            $webToken = WebToken::where('token', $token)->with('user')->first();
            
            if ($webToken && $webToken->user) {
                // Authentifier l'utilisateur
                Auth::login($webToken->user);
            }
        }
        
        return $next($request);
    }
}
