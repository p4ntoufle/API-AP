<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\GPBController;

/// INSCRIPTION ///
Route::post('/auth/register', [AuthController::class, 'register']);
/// CONNEXION ///
Route::post('/auth/login', [AuthController::class, 'login']);

/// MIDDLEWARE POUR SE DÉCONNECTER ET RÉCUPÉRER LES INFORMATIONS DU COMPTE ///
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

/// ROUTES POUR LES PENSIONS ///
/// Récupérer toutes les pensions ///
Route::get('/pensions', [GPBController::class, 'index']);
/// Ajouter une pension ///
Route::post('/pensions', [GPBController::class, 'store']);
