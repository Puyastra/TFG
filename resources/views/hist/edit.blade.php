@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Editar historia</h1>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-status mb-4"> 
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('historias.update', $historia->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="titulo" class="block font-semibold">Título</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $historia->titulo) }}" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="sinopsis" class="block font-semibold">Sinopsis</label>
            <textarea id="sinopsis" name="sinopsis" rows="5" class="w-full border p-2 rounded" required>{{ old('sinopsis', $historia->sinopsis) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="portada" class="block font-semibold">Imagen de Portada (opcional)</label>
            <input type="file" id="portada" name="portada" accept="image/*" class="w-full border p-2 rounded">
            @if ($historia->portada)
                <p class="mt-2 text-gray-600">Portada actual:</p>
                <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada actual" class="mt-2 rounded" style="max-width: 200px; height: auto; display: block;">
                <label class="block mt-2 text-sm text-gray-700">
                    <input type="checkbox" name="clear_portada" value="1" class="mr-2"> Eliminar portada actual
                </label>
            @endif
        </div>

        <div class="mb-4">
            <label for="estado" class="block font-semibold">Estado</label>
            <select id="estado" name="estado" class="w-full border p-2 rounded" required>
                <option value="publicada" {{ old('estado', $historia->estado) == 'publicada' ? 'selected' : '' }}>Publicada</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="categorias" class="block font-semibold">Categorías (separadas por comas)</label>
            <input type="text" id="categorias" name="categorias"
                   value="{{ old('categorias', $historia->categorias->pluck('name')->implode(', ')) }}"
                   placeholder="Ej: Fútbol, Pizza, Misterio" class="w-full border p-2 rounded">
            <small class="block text-sm text-gray-600 mt-1">Edita las categorías separadas por comas. Las nuevas se crearán, las eliminadas se desasociarán.</small>
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Guardar cambios
        </button>
    </form>
@endsection