<?php

namespace App\Http\Controllers;
use App\Models\Capitulo_historia_like;
use App\Models\Capitulo_historia;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function index()
    {
        return Capitulo_historia_like::all();
    }

    public function show(Capitulo_historia_like $like)
    {
        return $like;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'capitulo_historia_id' => 'required|exists:capitulo_historias,id',
            'capitulo_historia_comentario_id' => 'nullable|exists:capitulo_historia_comentarios,id',
        ]);
        return Capitulo_historia_like::create($validated);
    }

    public function update(Request $request, Capitulo_historia_like $like)
    {
        $like->update($request->all());
        return $like;
    }

    public function destroy(Capitulo_historia_like $like)
    {
        $like->delete();
        return response(null, 204);
    }

    public function toggleCapituloLike($capituloId)
    {
        $userId = auth()->id();

        $capitulo = Capitulo_historia::find($capituloId);

        if (!$capitulo || !$capitulo->historia_id) {
            return back()->with('error', 'El capítulo o su historia asociada no se encontraron.');
        }

        $like = Capitulo_historia_like::where('user_id', $userId)
            ->where('capitulo_historia_id', $capituloId)
            ->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Has quitado tu voto.');
        }

        Capitulo_historia_like::create([
            'user_id' => $userId,
            'capitulo_historia_id' => $capituloId,
            'historia_id' => $capitulo->historia_id, 
            'capitulo_historia_comentario_id' => null,
        ]);

        return back()->with('success', 'Has votado este capítulo.');
    }

    public function getByCapitulo($capituloId)
    {
        return Capitulo_historia_like::where('capitulo_historia_id', $capituloId)->get();
    }

}
