<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animaux';

    protected $fillable = ['nom', 'espece', 'race', 'user_id'];
}
