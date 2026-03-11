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
        'adresse',
        'ville',
        'region',
        'code_postal',
        'telephone',
        'email',
        'description',
        'capacite_chiens',
        'capacite_chats',
        'image',
        'directeur_nom',
        'directeur_mail',
        'services',
        'horaires',
        'prix_chien_jour',
        'prix_chat_jour',
        'actif',
        'famille'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'factures', 'pension_id', 'user_id')->distinct();
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
}
