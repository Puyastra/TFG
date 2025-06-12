<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Librería</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @vite("resources/css/app.css")
</head>
<body class="grid grid-cols-[auto_1rf_auto] min-h-screen">
    <header class="navbar">
        <div class="navbar-left">
            <a href="{{ route('inicio') }}" class="logo">La Librería</a>
        </div>

        {{-- Formulario de búsqueda --}}
        <div class="navbar-search">
            <form action="{{ route('search.results') }}" method="GET">
                <input type="text" name="query" placeholder="Buscar historias o usuarios..." class="search-input" value="{{ request('query') }}">
                <button type="submit" class="search-button">🔍Buscar</button>
            </form>
        </div>

        <div class="navbar-right flex gap-4">
            @auth
                <div class="dropdown">
                    <button class="dropbtn">Escribir ⌄</button>
                    <div class="dropdown-content">
                        <a href="{{ route('historias.create') }}">Crear historia</a>
                        <a href="{{ route('historias.index') }}">Mis historias</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">{{ Auth::user()->name }} ⌄</button>
                    <div class="dropdown-content">
                        <a href="{{ route('perfil') }}">Mi perfil</a>
                        {{-- Formulario de Logout oculto, se activará con JavaScript --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        {{-- Botón visible que usa JavaScript para enviar el formulario --}}
                        <button type="button" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar sesión
                        </button>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Bookworm. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
