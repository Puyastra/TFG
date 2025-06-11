<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class FollowController extends Controller
{
    // Asegurarse de que solo los usuarios autenticados puedan seguir/dejar de seguir
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Alterna el estado de seguimiento entre el usuario autenticado y otro usuario.
     * Si el usuario logueado ya sigue al otro usuario, deja de seguirlo.
     * Si no lo sigue, empieza a seguirlo.
     *
     * @param  \App\Models\User  $user El usuario al que se quiere seguir/dejar de seguir (usando Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFollow(User $user)
    {
        // Obtener el usuario autenticado
        $follower = Auth::user();

        // No permitir que un usuario se siga a sí mismo
        if ($follower->id === $user->id) {
            return back()->with('error', 'No puedes seguirte a ti mismo.');
        }

        // Verificar si el usuario autenticado ya sigue a este usuario
        // La relación followings() fue definida en el modelo User
        if ($follower->isFollowing($user)) {
            // Si ya lo sigue, dejar de seguir (detach)
            $follower->followings()->detach($user->id);
            $message = 'Has dejado de seguir a ' . $user->name . '.';
        } else {
            // Si no lo sigue, empezar a seguir (attach)
            $follower->followings()->attach($user->id);
            $message = 'Ahora sigues a ' . $user->name . '.';
        }

        // Redirigir de vuelta a la página del perfil del usuario al que se siguió/dejó de seguir
        return redirect()->route('perfil.show', $user->id)->with('success', $message);
    }
}
