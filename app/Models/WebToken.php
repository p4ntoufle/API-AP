<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebToken extends Model
{
    protected $table = 'web_tokens';
    
    protected $fillable = ['user_id', 'token'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
