<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ORAL

class Option extends Model
{
    protected $fillable = ['pension_id', 'libelle', 'tarif'];

    public function pension()
    {
        return $this->belongsTo(Pension::class);
    }
}
