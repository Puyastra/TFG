<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capitulo_historia_comentario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'capitulo_historia_id',
        'comentario',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function capitulo_historia()
    {
        return $this->belongsTo(Capitulo_historia::class);
    }
    public function capitulo_historia_like()
    {
        return $this->hasMany(Capitulo_historia_like::class);
    }
}
