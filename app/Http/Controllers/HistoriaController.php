<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historia;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistoriaController extends Controller
{
    /**
     * Muestra las historias del usuario autenticado.
     * Si no hay usuario autenticado, redirige o muestra un mensaje.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus historias.');
        }

        $historias = Auth::user()->historia()->with('categorias', 'capitulo_historia')->latest()->get();

        return view('misHistorias', compact('historias'));
    }

    public function show($id)
    {
        // Se asegura de cargar las relaciones necesarias para miHistoria.blade.php
        $historia = Historia::with('user', 'categorias', 'capitulo_historia')->findOrFail($id);
        return view('miHistoria', compact('historia'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'nullable|string',
            'estado' => 'required|in:publicada,borrador',
            'portada' => 'nullable|image|max:2048',
            'categorias' => 'nullable|string|max:500',
        ]);

        $portadaPath = null;
        if ($request->hasFile('portada')) {
            $nombreArchivo = time() . '_' . $request->file('portada')->getClientOriginalName();
            $portadaPath = $request->file('portada')->storeAs('portadas', $nombreArchivo, 'public');
        }

        $historia = Historia::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'sinopsis' => $validated['sinopsis'],
            'estado' => $validated['estado'],
            'portada' => $portadaPath,
        ]);

        if ($request->filled('categorias')) {
            $categoryNames = array_map('trim', explode(',', $validated['categorias']));
            $categoryIds = [];

            foreach ($categoryNames as $categoryName) {
                if (!empty($categoryName)) {
                    $category = Categories::firstOrCreate(['name' => ucfirst(strtolower($categoryName))]);
                    $categoryIds[] = $category->id;
                }
            }
            $historia->categorias()->attach(array_unique($categoryIds));
        }

        return redirect()->route('perfil')->with('success', 'Historia creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $historia = Historia::findOrFail($id);

        if (auth()->id() !== $historia->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'estado' => 'required|in:publicada,borrador',
            'portada' => 'nullable|image|max:2048',
            'categorias' => 'nullable|string|max:500',
        ]);

        $portadaPath = $historia->portada;
        if ($request->hasFile('portada')) {
            if ($historia->portada) {
                Storage::disk('public')->delete($historia->portada);
            }
            $nombreArchivo = time() . '_' . $request->file('portada')->getClientOriginalName();
            $portadaPath = $request->file('portada')->storeAs('portadas', $nombreArchivo, 'public');
        } else if ($request->input('clear_portada')) {
             if ($historia->portada) {
                 Storage::disk('public')->delete($historia->portada);
             }
             $portadaPath = null;
        }

        $historia->update([
            'titulo' => $validated['titulo'],
            'sinopsis' => $validated['sinopsis'],
            'estado' => $validated['estado'],
            'portada' => $portadaPath,
        ]);

        if ($request->filled('categorias')) {
            $categoryNames = array_map('trim', explode(',', $validated['categorias']));
            $categoryIds = [];

            foreach ($categoryNames as $categoryName) {
                if (!empty($categoryName)) {
                    $category = Categories::firstOrCreate(['name' => ucfirst(strtolower($categoryName))]);
                    $categoryIds[] = $category->id;
                }
            }
            $historia->categorias()->sync(array_unique($categoryIds));
        } else {
            $historia->categorias()->detach();
        }

        return redirect()->route('historias.show', $historia->id)->with('success', 'Historia actualizada correctamente.');
    }

    /**
     * Elimina una historia del usuario autenticado.
     *
     * @param  int  $id  ID de la historia a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) 
    {
        $historia = Historia::findOrFail($id); 

        if (auth()->id() !== $historia->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Eliminar la portada si existe
        if ($historia->portada) {
            Storage::disk('public')->delete($historia->portada);
        }

        $historia->delete();
        // Redirige al perfil como habías pedido.
        return redirect()->route('perfil')->with('success', 'Historia eliminada correctamente.');
    }

    public function create()
    {
        return view('hist.create');
    }

    public function edit($id)
    {
        $historia = Historia::with('categorias')->findOrFail($id);

        if (auth()->id() !== $historia->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('hist.edit', compact('historia'));
    }
}
