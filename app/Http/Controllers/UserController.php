<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ¡Importa Storage!

class UserController extends Controller
{

    public function perfil()
    {
        $user = Auth::user();
        $user->load('historia');
        return view('perfil', compact('user'));
    }

    public function mostrarPerfil($id)
    {
        $user = User::with('historia')->findOrFail($id);
        return view('perfil', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('editarPerfil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048', 
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'bio' => $validated['bio'],
        ];

        // --- Lógica para el Avatar ---
        if ($request->hasFile('avatar')) {
            // Eliminar avatar antiguo si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Guardar nuevo avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        } elseif ($request->has('clear_avatar')) {
            // Eliminar avatar si se marcó la casilla "Eliminar avatar actual"
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = null;
        }

        // --- Lógica para el Banner ---
        if ($request->hasFile('banner')) {
            // Eliminar banner antiguo si existe
            if ($user->banner) {
                Storage::disk('public')->delete($user->banner);
            }
            // Guardar nuevo banner
            $bannerPath = $request->file('banner')->store('banners', 'public');
            $userData['banner'] = $bannerPath;
        } elseif ($request->has('clear_banner')) {
            // Eliminar banner si se marcó la casilla "Eliminar banner actual"
            if ($user->banner) {
                Storage::disk('public')->delete($user->banner);
            }
            $userData['banner'] = null; // Establecer a null en la DB
        }

        // Actualizar los datos del usuario en la base de datos
        $user->update($userData);

        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
