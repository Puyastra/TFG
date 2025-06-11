    @extends('layouts.app')

    @section('content')
        <div class="search-results-container">
            <h1>Resultados de búsqueda para "{{ $query }}"</h1>

            @if ($historias->isEmpty() && $users->isEmpty())
                <p class="no-results">No se encontraron resultados para tu búsqueda.</p>
            @else
                {{-- Sección de Historias --}}
                <div class="search-section">
                    <h2>Historias encontradas ({{ $historias->count() }})</h2>
                    @if ($historias->isNotEmpty())
                        <div class="historias-search-grid">
                            @foreach ($historias as $historia)
                                <div class="historia-index-card">
                                    @if($historia->portada)
                                        <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada de {{ $historia->titulo }}">
                                    @endif
                                    <h3>{{ $historia->titulo }}</h3>
                                    <p>{{ Str::limit($historia->sinopsis, 100) }}</p>
                                    <div class="Autor-leer">
                                        <small>Por {{ $historia->user->name }}</small>
                                        <a href="{{ route('historias.show', $historia->id) }}">Leer historia</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-results">No se encontraron historias que coincidan con tu búsqueda.</p>
                    @endif
                </div>

                {{-- Sección de Usuarios --}}
                <div class="search-section">
                    <h2>Usuarios encontrados ({{ $users->count() }})</h2>
                    @if ($users->isNotEmpty())
                        <div class="users-search-grid">
                            @foreach ($users as $user)
                                <div class="user-card">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar.jpg') }}" alt="Avatar de {{ $user->name }}">
                                    <h3><a href="{{ route('perfil.show', $user->id) }}">{{ $user->name }}</a></h3>
                                    <p>{{ Str::limit($user->bio ?? 'Sin biografía.', 50) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="no-results">No se encontraron usuarios que coincidan con tu búsqueda.</p>
                    @endif
                </div>
            @endif
        </div>
    @endsection
    