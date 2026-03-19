<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\GPBController;
use App\Http\Controllers\API\FaSeController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EspeceController;

/// INSCRIPTION ///
Route::post('/auth/register', [AuthController::class, 'register']);
/// CONNEXION ///
Route::post('/auth/login', [AuthController::class, 'login']);

/// ROUTES PROTÉGÉES PAR SANCTUM ///
Route::middleware('auth:sanctum')->group(function () {

    /// Auth ///
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/me', [AuthController::class, 'update']);

    /// Espèces ///
    Route::get('/especes', [EspeceController::class, 'index']);

    /// Animaux ///
    Route::get('/animaux', [AnimalController::class, 'index']);
    Route::get('/animaux/{id}', [AnimalController::class, 'show']);
    Route::post('/animaux', [AnimalController::class, 'store']);
    Route::put('/animaux/{id}', [AnimalController::class, 'update']);
    Route::delete('/animaux/{id}', [AnimalController::class, 'destroy']);

    /// Factures ///
    Route::get('/factures', [FaSeController::class, 'index']);
    Route::get('/factures/{facture}/download', [FaSeController::class, 'download'])
        ->name('api.factures.download');
});

/// ROUTES POUR LES PENSIONS ///
Route::get('/pensions', [GPBController::class, 'index']);
Route::post('/pensions', [GPBController::class, 'store']);
Route::put('/pensions/{id}/update', [GPBController::class, 'update']);

/// ROUTES POUR LES FICHES ///
Route::get('/fiches', [GPBController::class, 'show']);
Route::put('/fiches/{fiche}', [GPBController::class, 'update']);
