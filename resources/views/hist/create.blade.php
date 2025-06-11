@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div class="form-container">
        <h1>Crear nueva historia</h1>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-status">
                {{ session('success') }}
            </div>
        @endif
        {{-- Fin mensaje de éxito --}}

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('historias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="titulo">Título de la historia</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required> {{-- Añadido old('titulo') --}}

            <label for="sinopsis">Sinopsis</label>
            <textarea name="sinopsis" id="sinopsis" rows="5" required>{{ old('sinopsis') }}</textarea> {{-- Añadido old('sinopsis') --}}

            <label for="portada">Imagen de portada (opcional)</label>
            <input type="file" name="portada" id="portada">

            <label for="estado">Estado de publicación</label>
            <select name="estado" id="estado" required>
                <option value="publicada" {{ old('estado') == 'publicada' ? 'selected' : '' }}>Publicada</option> {{-- Añadido old('estado') --}}
            </select>

            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <label for="categorias">Categorías (separadas por comas)</label>
            <input type="text" name="categorias" id="categorias" placeholder="Ej: fútbol, pizza, misterio" value="{{ old('categorias') }}"> {{-- Asegurado old('categorias') --}}
            <small>Ejemplo: <i>fútbol, pizza, misterio</i>. Las categorías se crearán si no existen.</small>

            <button type="submit">Publicar historia</button>
        </form>
    </div>
@endsection
