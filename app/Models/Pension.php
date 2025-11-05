<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model {
    protected $fillable = ['nom', 'adresse', 'ville', 'description'];
}
