<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoadUserFromJWT
{
    /**
     * Charge l'utilisateur depuis le token JWT (cookie ou header)
     */
    public function handle(Request $request, Closure $next)
    {
        // Cherche d'abord le token dans le header Authorization
        $token = $request->bearerToken();
        
        // Sinon, cherche dans le cookie
        if (!$token && $request->hasCookie('auth_token')) {
            $token = $request->cookie('auth_token');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        if ($token) {
            try {
                $user = JWTAuth::authenticate($token);
                if ($user) {
                    auth('web')->setUser($user);
                }
            } catch (\Exception $e) {
                // Token invalide, continuer sans authentification
            }
        }

        return $next($request);
    }
}
