<?php

namespace App\Http\Controllers;

use App\Models\Espece;
use Illuminate\Http\Request;

class EspeceController extends Controller
{
    /**
     * Retourne la liste de toutes les espèces.
     */
    public function index()
    {
        return response()->json(Espece::all());
    }
}
