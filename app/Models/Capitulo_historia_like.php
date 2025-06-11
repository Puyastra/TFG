<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capitulo_historia_like extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'capitulo_historia_id',
        'historia_id',
        'capitulo_historia_comentario_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function historia()
    {
        return $this->belongsTo(Historia::class);
    }
    public function capitulo_historia()
    {
        return $this->belongsTo(Capitulo_historia::class);
    }
    public function capitulo_historia_comentario()
    {
        return $this->belongsTo(Capitulo_historia_comentario::class);
    }
}