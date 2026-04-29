<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthController extends Controller
{
    /**
     * Génère un JWT token après authentification réussie
     */
    public static function generateToken($user)
    {
        return JWTAuth::fromUser($user);
    }

    /**
     * Retourne le user actuel depuis le JWT
     */
    public static function getCurrentUser()
    {
        return auth('web')->user();
    }
}
