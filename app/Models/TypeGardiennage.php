<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeGardiennage extends Model
{
    protected $fillable = ['pension_id', 'libelle'];

    public function pension()
    {
        return $this->belongsTo(Pension::class);
    }
}

