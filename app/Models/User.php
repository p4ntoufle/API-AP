<?php

namespace App\Models;
use App\Models\Animaux;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Identifiants
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Attributs à cacher
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     *
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pensions()
    {
        return $this->belongsToMany(Pension::class, 'factures', 'user_id', 'pension_id')->distinct();
    }

    public function pension()
    {
        return $this->hasOne(Pension::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function animaux()
    {
        return $this->hasMany(Animaux::class, 'user_id');
    }
}
