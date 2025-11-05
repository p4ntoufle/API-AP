<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pension;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
    public function home() {
        return view('home');
    }

    public function pensions() {
        $pensions = Pension::all();
        return view('pensions', compact('pensions'));
    }

    public function contact() {
        return view('contact');
    }

    // Auth web (indépendant de Sanctum)
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home')->with('success', 'Connexion réussie');
        }
        return back()->with('error', 'Identifiants invalides');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('home');
    }
}
