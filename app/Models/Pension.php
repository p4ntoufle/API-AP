<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pension extends Model {
    /**
     * Informations
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'ville',
        'adresse',
        'telephone',
        'responsable'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'factures', 'pension_id', 'user_id')->distinct();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }

    public function typesGardiennage()
    {
        return $this->hasMany(TypeGardiennage::class);
    }

    public function tarifs()
    {
        return $this->hasMany(Tarif::class);
    }

    // ORAL

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
