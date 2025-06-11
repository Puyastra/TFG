@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div class="auth-container">
        <h2>Crear una cuenta</h2>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>

            <button type="submit">Registrarse</button>
        </form>

        <p class="auth-switch">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </div>
@endsection
