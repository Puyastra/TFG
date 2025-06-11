<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historia;
use App\Models\User;     

class SearchController extends Controller
{
    /**
     * Maneja la solicitud de búsqueda.
     * Busca historias por título y sinopsis, y usuarios por nombre.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda de la solicitud
        $query = $request->input('query');

        // Inicializar colecciones de resultados vacías
        $historias = collect();
        $users = collect();

        // Si hay un término de búsqueda, realizar la búsqueda
        if ($query) {
            // Buscar historias por título o sinopsis
            $historias = Historia::where('titulo', 'LIKE', "%{$query}%")
                                 ->orWhere('sinopsis', 'LIKE', "%{$query}%")
                                 ->where('estado', 'publicada') // Solo historias publicadas
                                 ->with('user') // Cargar la relación con el usuario para mostrar el autor
                                 ->get();

            // Buscar usuarios por nombre
            $users = User::where('name', 'LIKE', "%{$query}%")
                          ->get();
        }

        // Pasar los resultados a la vista de resultados de búsqueda
        return view('resultadoBusqueda', compact('historias', 'users', 'query'));
    }
}

