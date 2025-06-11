<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capitulo_historia extends Model
{
    use HasFactory;

    protected $fillable = [
        'historia_id',
        'titulo', 
        'introduccion',
        'contenido',
    ];

    public function historia(){
        return $this->belongsTo(Historia::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Capitulo_historia_comentario::class);
    }

    public function likes()
    {
        return $this->hasMany(Capitulo_historia_like::class);
    }
}