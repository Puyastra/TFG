@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div class="perfil-container">
        <!-- Banner superior -->
        <div class="perfil-banner">
            {{-- Muestra el banner del usuario si existe, sino usa el por defecto --}}
            <img src="{{ $user->banner ? asset('storage/' . $user->banner) : asset('storage/avatars/12225881.png') }}" alt="Banner de perfil">
        </div>

        <div class="avatar">
            {{-- Muestra el avatar del usuario si existe, sino usa el por defecto --}}
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/12225881.png') }}" alt="Avatar de {{ $user->name }}">
        </div>

        <!-- Contenedor de info de usuario (la tarjeta blanca que se superpone) -->
        <div class="perfil-info">
            {{-- Contenido de usuario-info ahora solo texto, sin el avatar aquí --}}
            <div class="usuario-info">
                <h1 class="nombre-usuario">{{ $user->name }}</h1>
                <p class="bio-usuario">{{ $user->bio ?? 'Amante de la escritura y la lectura. Bienvenido a mi mundo de historias.' }}</p>
                <div class="estadisticas">
                    <span><strong>{{ $user->historia->count() }}</strong>
                        obra{{ $user->historia->count() !== 1 ? 's' : '' }}</span>
                    {{-- Mostrar el número real de seguidores usando la relación del modelo User --}}
                    <span><strong>{{ $user->followers_count }}</strong>
                        seguidor{{ $user->followers_count !== 1 ? 'es' : '' }}</span>
                </div>
                <p><strong>Ubicación:</strong> {{ $user->location ?? 'España' }}</p>
                <p><strong>Miembro desde:</strong> {{ $user->created_at->format('d M, Y') }}</p>

                {{-- === INICIO BOTONES DE ACCIÓN EN EL PERFIL (Editar / Seguir) === --}}
                @auth
                    {{-- Si es el perfil del usuario logueado, mostrar botón de "Editar Perfil" --}}
                    @if (Auth::id() == $user->id)
                        <div style="margin-top: 20px;">
                            <a href="{{ route('perfil.edit') }}" class="btn-editar-perfil">Editar Perfil</a>
                        </div>
                    @else
                        {{-- Si es el perfil de otro usuario, mostrar botón de "Seguir" / "Dejar de Seguir" --}}
                        <div style="margin-top: 20px;">
                            <form action="{{ route('users.toggleFollow', $user->id) }}" method="POST">
                                @csrf
                                {{-- Usamos el método isFollowing() definido en el modelo User --}}
                                @if (Auth::user()->isFollowing($user))
                                    <button type="submit" class="btn-follow btn-unfollow">Dejar de Seguir</button>
                                @else
                                    <button type="submit" class="btn-follow btn-do-follow">Seguir</button>
                                @endif
                            </form>
                        </div>
                    @endif
                @endauth
                {{-- === FIN BOTONES DE ACCIÓN EN EL PERFIL === --}}

                {{-- Mensajes de éxito/error de follow --}}
                @if(session('success'))
                    <div class="alert alert-status" style="margin-top: 15px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error" style="margin-top: 15px;">
                        {{ session('error') }}
                    </div>
                @endif

            </div> {{-- Cierre de usuario-info --}}
        </div> {{-- Cierre de perfil-info --}}

        <!-- Historias del usuario -->
        <div class="historias-container">
            <h2>Mis historias</h2>
            <div class="historias-grid">
                @forelse ($user->historia as $historia)
                    <div class="tarjeta-historia">
                        @if($historia->portada)
                            <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada de {{ $historia->titulo }}">
                        @endif
                        <div class="contenido-historia">
                            <h3>{{ $historia->titulo }}</h3>
                            <p>{{ Str::limit($historia->sinopsis, 100) }}</p>
                            <span class="estado">Estado: <strong>{{ ucfirst($historia->estado) }}</strong></span>

                            {{-- Mostrar contador de visitas --}}
                            <small>Visitas: {{ $historia->views_count }}</small>
                            {{-- Mostrar contador de votos totales --}}
                            <small>Votos: {{ $historia->total_likes_count }}</small>
                            {{-- Fin contador de votos --}}

                            {{-- Mostrar categorías como tags --}}
                            @if ($historia->categorias->isNotEmpty())
                                <div class="category-tags-container" style="margin-top: 5px; margin-bottom: 5px;">
                                    @foreach($historia->categorias as $categoria)
                                        <span class="category-tag">{{ $categoria->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            {{-- Fin mostrar categorías --}}

                            <a href="{{ route('historias.show', $historia->id) }}" class="btn-leer">Leer más</a>
                        </div>
                    </div>
                @empty
                    <p class="sin-historias">No has escrito ninguna historia todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
