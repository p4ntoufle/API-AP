<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'pension_id',
        'numero',
        'issued_at',
        'stay_start_at',
        'stay_end_at',
        'animals_count',
        'total_ht',
        'total_ttc',
        'pdf_path',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at' => 'date',
        'stay_start_at' => 'date',
        'stay_end_at' => 'date',
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pension(): BelongsTo
    {
        return $this->belongsTo(Pension::class);
    }
}
