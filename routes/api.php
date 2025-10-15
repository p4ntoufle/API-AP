<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/// INSCRIPTION ///
Route::post('/auth/register', [AuthController::class, 'register']);
/// CONNEXION ///
Route::post('/auth/login', [AuthController::class, 'login']);

/// MIDDLEWARE POUR SE DÉCONNECTER ET RÉCUPÉRER LES INFORMATIONS DU COMPTE ///
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});
