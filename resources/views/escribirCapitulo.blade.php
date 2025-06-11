@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('style.css') }}">
<div id="writer-editor">
    <h2>Escribir Nuevo Capítulo para "{{ $historia->titulo }}"</h2>

    <form action="{{ route('capitulos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="historia_id" value="{{ $historia->id }}">

        <div>
            <label for="titulo">Título del Capítulo</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
            @error('titulo')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="introduccion">Introducción (Opcional)</label>
            <textarea id="introduccion" name="introduccion" rows="4">{{ old('introduccion') }}</textarea>
            @error('introduccion')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contenido">Contenido del Capítulo</label>
            <textarea id="contenido" name="contenido" rows="20" required></textarea>
            @error('contenido')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <a href="{{ route('historias.show', $historia->id) }}">Cancelar</a>
            <button type="submit">Guardar Capítulo</button>
        </div>
    </form>
</div>
@endsection
