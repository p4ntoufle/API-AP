<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\GPBController;
use App\Http\Controllers\API\FaSeController;
use App\Http\Controllers\AnimalController;

/// INSCRIPTION ///
Route::post('/auth/register', [AuthController::class, 'register']);
/// CONNEXION ///
Route::post('/auth/login', [AuthController::class, 'login']);

/// MIDDLEWARE POUR SE DÉCONNECTER ET RÉCUPÉRER LES INFORMATIONS DU COMPTE ///
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/factures/{facture}/download', [FaSeController::class, 'download'])
        ->name('api.factures.download');
    Route::apiResource('factures', FaSeController::class);
});

/// ROUTES POUR LES PENSIONS ///
/// Récupérer toutes les pensions ///
Route::get('/pensions', [GPBController::class, 'index']);
/// Ajouter une pension ///
Route::post('/pensions', [GPBController::class, 'store']);
/// Modifier une pension ///
Route::put('/pensions/{id}/update', [GPBController::class, 'update']);

/// ROUTES POUR LES FICHES ///
/// Récupérer la pension
Route::get('/fiches', [GPBController::class, 'show']);


Route::get('/animaux', [AnimalController::class, 'index']);
Route::get('/animaux/{id}', [AnimalController::class, 'show']);
Route::post('/animaux', [AnimalController::class, 'store']);
Route::put('/animaux/{id}', [AnimalController::class, 'update']);
Route::delete('/animaux/{id}', [AnimalController::class, 'destroy']);
