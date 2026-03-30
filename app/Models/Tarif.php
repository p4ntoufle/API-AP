<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $fillable = ['pension_id', 'type_gardiennage_id', 'prix'];

    public function pension()
    {
        return $this->belongsTo(Pension::class);
    }

    public function typeGardiennage()
    {
        return $this->belongsTo(TypeGardiennage::class);
    }
}

