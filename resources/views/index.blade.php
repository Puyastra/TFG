@extends('layouts.app')

@section('content')
    <section class="historias-recientes-section">
        <h1>Historias recientes</h1>

        <div class="historias-index-grid">
            @forelse($historias as $historia)
                <div class="historia-index-card">
                    @if($historia->portada)
                        <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada de {{ $historia->titulo }}">
                    @endif

                    <h3>{{ $historia->titulo }}</h3>
                    <p>{{ Str::limit($historia->sinopsis, 120) }}</p>

                    {{-- Mostrar categorías --}}
                    @if ($historia->categorias->isNotEmpty())
                        <div class="category-tags-container">
                            @foreach($historia->categorias as $categoria)
                                <span class="category-tag">{{ $categoria->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    {{-- Fin mostrar categorías --}}

                    <div class="Autor-leer">
                        <small>Por {{ $historia->user->name }}</small>
                        {{-- Mostrar contador de visitas --}}
                        <small>Visitas: {{ $historia->views_count }}</small>
                        {{-- Mostrar contador de votos totales --}}
                        <small>Votos: {{ $historia->total_likes_count }}</small>
                        {{-- Fin contador de votos --}}
                        <a href="{{ route('capitulos.index', ['id' => $historia->id]) }}">Empezar a leer</a>
                    </div>
                </div>
            @empty
                <p>No hay historias publicadas aún.</p>
            @endforelse
        </div>
    </section>
@endsection
