<?php

namespace App\Http\Controllers;

use App\Models\Capitulo_historia_comentario;

use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function index()
    {
        return Capitulo_historia_comentario::all();
    }

    public function show(Capitulo_historia_comentario $comentario)
    {
        return $comentario;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'capitulo_historia_id' => 'required|exists:capitulo_historias,id',
            'comentario' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Capitulo_historia_comentario::create($validated);

        return back()->with('success', 'Comentario añadido correctamente.');
    }


    public function update(Request $request, Capitulo_historia_comentario $comentario)
    {
        $comentario->update($request->all());
        return $comentario;
    }

    public function destroy(Capitulo_historia_comentario $comentario)
    {
        $comentario->delete();
        return response(null, 204);
    }
    public function getByCapitulo($capituloId)
    {
        return Capitulo_historia_comentario::with('user')
            ->where('capitulo_historia_id', $capituloId)
            ->get();
    }
}
