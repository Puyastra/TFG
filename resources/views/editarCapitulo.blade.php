@extends('layouts.app')

@section('content')
    <div>
        <h1>Editar Capítulo</h1>

        @if(session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('capitulos.update', $capitulo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="titulo">Título del Capítulo</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $capitulo->titulo) }}" required>
            </div>

            <div>
                <label for="introduccion">Introducción (opcional)</label>
                <textarea id="introduccion" name="introduccion" rows="3">{{ old('introduccion', $capitulo->introduccion) }}</textarea>
            </div>

            <div>
                <label for="contenido">Contenido del Capítulo</label>
                <textarea id="contenido" name="contenido" rows="15" required>{{ old('contenido', $capitulo->contenido) }}</textarea>
            </div>

            <button type="submit">Guardar Cambios del Capítulo</button>
        </form>

        @if ($capitulo->historia)
            <div style="margin-top: 20px;">
                <a href="{{ route('historias.show', $capitulo->historia->id) }}">Volver a la Historia</a>
            </div>
        @endif
    </div>
@endsection
