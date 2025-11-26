<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animaux extends Model
{
    protected $table = 'animaux';

    protected $fillable = [
        'nom',
        'espece',
        'age',
        'description'
    ];
}
