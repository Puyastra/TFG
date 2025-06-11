<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Historia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'sinopsis',
        'portada',
        'estado'
    ];

    public function getTotalLikesCountAttribute()
    {
        return $this->storyLikes()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function capitulo_historia_like()
    {
        return $this->hasMany(Capitulo_historia_like::class);
    }
    public function storyLikes(): HasMany
    {
        return $this->hasMany(Capitulo_historia_like::class, 'historia_id');
    }

    public function capitulo_historia()
    {
        return $this->hasMany(Capitulo_historia::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categories::class, 'category_historia', 'historia_id', 'category_id');
    }
}
