<?php

namespace App\Http\Controllers;

use App\Models\Capitulo_historia;
use App\Models\Historia;
use Illuminate\Http\Request;

class CapituloHistoriaController extends Controller
{
    public function index($id)
    {
        $capitulos = Historia::findOrfail($id)->capitulo_historia()->orderBy('id', 'asc')->paginate(10);
        return view('indexCapitulo', compact('capitulos'));
    }

    public function show($id)
    {
        $capitulo = Capitulo_historia::with([
            'historia',
            'likes',
            'comentarios.user',
            'comentarios.capitulo_historia_like'
        ])->findOrFail($id);

        if ($capitulo->historia) {
            $capitulo->historia->increment('views_count');
        }

        $historiaId = $capitulo->historia_id;

        $previousChapter = Capitulo_historia::where('historia_id', $historiaId)
                                            ->where('id', '<', $capitulo->id)
                                            ->orderBy('id', 'desc')
                                            ->first();

        $nextChapter = Capitulo_historia::where('historia_id', $historiaId)
                                        ->where('id', '>', $capitulo->id)
                                        ->orderBy('id', 'asc')
                                        ->first();

        return view('mostrarCapitulo', compact('capitulo', 'previousChapter', 'nextChapter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'historia_id' => 'required|exists:historias,id',
            'titulo' => 'required|string|max:255',
            'introduccion' => 'nullable|string',
            'contenido' => 'required|string',
        ]);

        $capitulo = Capitulo_historia::create($validated);

        return redirect()->route('historias.show', $request->historia_id)
            ->with('success', 'Capítulo creado correctamente.');
    }

    // Método update: Ajustado para recibir $id y cargar el modelo manualmente
    public function update(Request $request, $id) 
    {
        $capitulo = Capitulo_historia::findOrFail($id);

        // Cargar la relación 'historia' para la comprobación de autorización
        $capitulo->load('historia');
        
        if (!$capitulo->historia || auth()->id() !== $capitulo->historia->user_id) {
            abort(403, 'No tienes permiso para editar este capítulo o la historia asociada no existe.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'introduccion' => 'nullable|string',
            'contenido' => 'required|string',
        ]);

        $capitulo->update($validated);

        return redirect()->route('capitulos.show', $capitulo->id)
            ->with('success', 'Capítulo actualizado correctamente.');
    }

    // Método destroy: Ajustado para recibir $id y cargar el modelo manualmente
    public function destroy($id) 
    {
        $capitulo = Capitulo_historia::findOrFail($id); 

        // Cargar la relación 'historia' para la comprobación de autorización
        $capitulo->load('historia');

        // Doble verificación: si la historia no existe (caso de inconsistencia de DB)
        // o si el usuario no es el dueño.
        if (!$capitulo->historia || auth()->id() !== $capitulo->historia->user_id) {
            abort(403, 'No tienes permiso para eliminar este capítulo o la historia asociada no existe.');
        }

        $historiaId = $capitulo->historia_id; // Guarda el ID de la historia antes de eliminar el capítulo
        $capitulo->delete();

        // Redirigir a la página de la historia después de eliminar el capítulo
        return redirect()->route('historias.show', $historiaId) 
            ->with('success', 'Capítulo eliminado correctamente.');
    }

    public function create($historiaId)
    {
        $historia = Historia::findOrFail($historiaId);
        return view('escribirCapitulo', compact('historia'));
    }

    // Método edit: Ajustado para recibir $id y cargar el modelo manualmente
    public function edit($id) 
    {
        $capitulo = Capitulo_historia::findOrFail($id);

        // Cargar la relación 'historia' para la comprobación de autorización
        $capitulo->load('historia');

        if (!$capitulo->historia || auth()->id() !== $capitulo->historia->user_id) { 
            abort(403, 'No tienes permiso para editar este capítulo o la historia asociada no existe.');
        }

        return view('editarCapitulo', compact('capitulo'));
    }
}
