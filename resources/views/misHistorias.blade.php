@extends('layouts.app')

@section('content')
    <h1 class="page-title">Tus historias</h1>

    <div class="mis-historias-container">

        <div class="mis-historias-grid">
            @forelse($historias as $historia)
                <div class="mis-historias-card">
                    @if($historia->portada)
                        <img src="{{ asset('storage/' . $historia->portada) }}" alt="Portada de {{ $historia->titulo }}" class="mis-historias-portada">
                    @endif
                    <h2 class="mis-historias-title">{{ $historia->titulo }}</h2>
                    <p>{{ Str::limit($historia->sinopsis, 100) }}</p>

                    {{-- Mostrar categorías como tags --}}
                    @if ($historia->categorias->isNotEmpty())
                        <div class="category-tags-container">
                            @foreach($historia->categorias as $categoria)
                                <span class="category-tag">{{ $categoria->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    {{-- Fin mostrar categorías --}}

                    <div class="mis-historias-actions">
                        <a href="{{ route('historias.show', $historia->id) }}" class="btn-mis-historias-action">Ver Historia</a>
                        <form action="{{ route('historias.destroy', $historia->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-mis-historias-action btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar esta historia? Esto eliminará también todos sus capítulos y es irreversible.');">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="empty-message">No has creado ninguna historia aún.</p> {{-- Mensaje si no hay historias --}}
            @endforelse
        </div>
    </div>
@endsection
