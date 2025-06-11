@extends('layouts.app')

@section('content')
    <div class="historia-detalle-container">
        <h1>{{ $historia->titulo }}</h1>
        <p class="autor-info">Por: <a href="{{ route('perfil.show', $historia->user->id) }}">{{ $historia->user->name }}</a></p>

        @if($historia->portada)
            <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada de {{ $historia->titulo }}" class="historia-portada-detalle">
        @endif

        <p class="sinopsis-detalle">{{ $historia->sinopsis }}</p>

        @if ($historia->categorias->isNotEmpty())
            <div class="category-tags-container">
                @foreach($historia->categorias as $categoria)
                    <span class="category-tag">{{ $categoria->name }}</span>
                @endforeach
            </div>
        @endif

        {{-- Mostrar la cantidad de likes y visitas --}}
        <div class="historia-stats-detalle">
            <span>Likes: {{ $historia->total_likes_count }}</span>
            <span>Visitas: {{ $historia->views_count }}</span>
        </div>

        {{-- === INICIO NUEVOS BOTONES DE ACCIÓN PARA EL AUTOR === --}}
        @auth
            @if (Auth::id() == $historia->user_id)
                <div class="story-owner-actions">
                    <a href="{{ route('historias.edit', $historia->id) }}" class="btn-primary">Editar Historia</a>
                    <a href="{{ route('capitulos.create', ['historiaId' => $historia->id]) }}" class="btn-secondary">Añadir Capítulo</a>
                </div>
            @endif
        @endauth
        {{-- === FIN NUEVOS BOTONES DE ACCIÓN PARA EL AUTOR === --}}

        {{-- Sección de Capítulos --}}
        <div class="capitulos-section">
            <h2>Capítulos</h2>
            @if ($historia->capitulo_historia->isNotEmpty())
                <ul class="capitulos-list">
                    @foreach ($historia->capitulo_historia as $capitulo)
                        <li><a href="{{ route('capitulos.show', $capitulo->id) }}">{{ $capitulo->titulo }}</a></li>
                    @endforeach
                </ul>
            @else
                <p>Esta historia aún no tiene capítulos.</p>
            @endif
        </div>
    </div>
@endsection
