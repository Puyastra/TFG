@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div class="auth-container">
        <h2>Iniciar sesión</h2>

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <label>
            </label>

            <button type="submit">Entrar</button>
        </form>


        <p class="auth-switch">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate ahora</a>
        </p>
    </div>
@endsection