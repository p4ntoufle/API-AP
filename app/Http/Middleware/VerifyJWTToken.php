<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class VerifyJWTToken
{
    /**
     * Vérifie et authentifie le token JWT depuis le header Authorization
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Récupère le token du header Authorization: Bearer <token>
            $token = JWTAuth::getToken();
            
            if (!$token) {
                return redirect()->route('login')->with('error', 'Token not provided');
            }

            // Vérifie et authentifie le token
            $user = JWTAuth::authenticate($token);
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'User not found');
            }

        } catch (TokenExpiredException $e) {
            return redirect()->route('login')->with('error', 'Token expired');
        } catch (JWTException $e) {
            return redirect()->route('login')->with('error', 'Invalid token');
        }

        return $next($request);
    }
}
