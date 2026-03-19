<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animaux extends Model
{
    protected $table = 'animaux';

    protected $fillable = [
        'user_id',
        'nom',
        'espece',
        'age',
        'poids',
        'description',
        'carnet_vaccination',
        'vaccin_a_jour',
        'vermifuge_a_jour',
    ];

    protected $casts = [
        'carnet_vaccination' => 'boolean',
        'vaccin_a_jour'      => 'boolean',
        'vermifuge_a_jour'   => 'boolean',
        'poids'              => 'float',
    ];
}
