@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div class="form-container">
        <h1>Editar Perfil</h1>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="alert alert-status">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensajes de error de validación --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Usamos el método PUT para actualizar recursos --}}

            <div>
                <label for="name">Nombre de Usuario:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div>
                <label for="bio">Biografía:</label>
                <textarea id="bio" name="bio" rows="5" placeholder="Escribe algo sobre ti...">{{ old('bio', $user->bio ?? '') }}</textarea>
                {{-- Usamos $user->bio ?? '' por si la columna bio no existe o es null --}}
            </div>

            {{-- Campo para la imagen de Avatar --}}
            <div style="margin-top: 20px;">
                <label for="avatar">Foto de Perfil (Avatar)</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
                @if ($user->avatar)
                    <p style="margin-top: 10px;">Avatar actual:</p>
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar actual" style="max-width: 100px; height: auto; border-radius: 50%;">
                    <label style="display: block; margin-top: 5px;">
                        <input type="checkbox" name="clear_avatar" value="1"> Eliminar avatar actual
                    </label>
                @endif
            </div>

            {{-- Campo para la imagen de Banner --}}
            <div style="margin-top: 20px;">
                <label for="banner">Imagen de Banner</label>
                <input type="file" id="banner" name="banner" accept="image/*">
                @if ($user->banner)
                    <p style="margin-top: 10px;">Banner actual:</p>
                    <img src="{{ asset('storage/' . $user->banner) }}" alt="Banner actual" style="max-width: 300px; height: auto;">
                    <label style="display: block; margin-top: 5px;">
                        <input type="checkbox" name="clear_banner" value="1"> Eliminar banner actual
                    </label>
                @endif
            </div>

            <button type="submit" style="margin-top: 20px;">Guardar Cambios del Perfil</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="{{ route('perfil') }}">Volver al Perfil</a>
        </div>
    </div>
@endsection
