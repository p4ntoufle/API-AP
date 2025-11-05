<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pension;

class SiteController extends Controller
{
    public function index() {
        return view('pages.home');
    }

    public function pensions() {
        $pensions = Pension::all();
        return view('pages.pensions', compact('pensions'));
    }

    public function contact() {
        return view('pages.contact');
    }
}
