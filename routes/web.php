<?php

use App\Http\Controllers\Web\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/pensions', [SiteController::class, 'pensions'])->name('pensions');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');

Route::get('/login', [SiteController::class, 'showLogin'])->name('login');
Route::post('/login', [SiteController::class, 'login']);
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');
