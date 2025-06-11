<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'location',
        'avatar',
        'banner',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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

    public function capitulo_historia_comentario()
    {
        return $this->hasMany(Capitulo_historia_comentario::class);
    }

    public function capitulo_historia_like()
    {
        return $this->hasMany(Capitulo_historia_like::class);
    }

    public function historia()
    {
        return $this->hasMany(Historia::class);
    }

    // === INICIO DE RELACIONES DE FOLLOWS ===

    /**
     * Usuarios a los que este usuario está siguiendo.
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'follows', 
            'follower_id',
            'following_id' 
        )->withTimestamps(); 
    }

    /**
     * Usuarios que están siguiendo a este usuario.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'follows', 
            'following_id', 
            'follower_id'
        )->withTimestamps();
    }

    /**
     * Verifica si este usuario está siguiendo a otro usuario dado.
     *
     * @param User $user El usuario a verificar si se está siguiendo.
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->followings()->where('following_id', $user->id)->exists();
    }

    /**
     * Accesorio para obtener el número de seguidores.
     * Se puede acceder como $user->followers_count.
     */
    public function getFollowersCountAttribute(): int
    {
        return $this->followers()->count();
    }

    // === FIN DE RELACIONES DE FOLLOWS ===
}
