<?php

use App\Http\Controllers\API\GPBController as GPBController;
use App\Http\Controllers\API\FaSeController as FactureController;
use App\Http\Controllers\Web\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/pensions', [SiteController::class, 'pensions'])->name('pensions');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/horaires', [SiteController::class, 'horaires'])->name('horaires');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/factures', [SiteController::class, 'factures'])->name('factures');

Route::get('/login', [SiteController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [SiteController::class, 'login']);
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/fiches', [SiteController::class, 'fiches'])->name('fiches');
    Route::put('/fiches/{fiche}', [GPBController::class, 'update'])->name('fiches.update');
    Route::get('/factures/{facture}/telecharger', [FactureController::class, 'download'])->name('factures.download');
});
