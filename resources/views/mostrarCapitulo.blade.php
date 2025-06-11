@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <div>
        <div>
            <h1>{{ $capitulo->titulo }}</h1>
            @if ($capitulo->historia)
                <p>De la historia:
                    <a href="{{ route('historias.show', ["id" => $capitulo->historia_id]) }}">
                        "{{ $capitulo->historia->titulo }}"
                    </a>
                </p>
            @endif

            {{-- Mostrar votos totales del capítulo --}}
            <div>
                <strong>Votos:</strong> {{ $capitulo->likes->count() }}

                @auth
                    @php
                        $userLike = $capitulo->likes->firstWhere('user_id', auth()->id());
                    @endphp

                    <form action="{{ route('capitulos.likes.toggle', $capitulo->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit">Votar</button>
                    </form>
                @endauth
            </div>
            <br>
            <hr>

            @if (session('success'))
                <div>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <hr>

            @if($capitulo->introduccion)
                <div>
                    <p>{{ $capitulo->introduccion }}</p>
                </div>
            @endif

            <hr>
            <br>

            <div>
                {!! nl2br(e($capitulo->contenido)) !!}
            </div>

            <hr>

            {{-- Sección de Navegación entre Capítulos --}}
            <div class="capitulo-navigation" style="display: flex; justify-content: space-between; margin: 20px 0;">
                @if ($previousChapter)
                    <a href="{{ route('capitulos.show', $previousChapter->id) }}" class="nav-button prev-button">
                        Capítulo Anterior
                    </a>
                @else
                    <span></span>
                @endif

                @if ($nextChapter)
                    <a href="{{ route('capitulos.show', $nextChapter->id) }}" class="nav-button next-button">
                        Capítulo Siguiente
                    </a>
                @else
                    <span></span>
                @endif
            </div>
            <hr>

            {{-- Caja de comentarios --}}
            <div>
                <h3>Comentarios ({{ $capitulo->comentarios->count() }})</h3>

                @auth
                    <form action="{{ route('comentarios.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="capitulo_historia_id" value="{{ $capitulo->id }}">
                        <textarea name="comentario" rows="3" required maxlength="255"
                            placeholder="Escribe un comentario..."></textarea><br>
                        <button type="submit">Comentar</button>
                    </form>
                @else
                    <p><a href="{{ route('login') }}">Inicia sesión</a> para comentar.</p>
                @endauth

                <div>
                    @foreach ($capitulo->comentarios as $comentario)
                        <div style="border:1px solid #ccc; margin:10px 0; padding:8px; border-radius:6px;">
                            <p><strong>{{ $comentario->user->name }}</strong> dijo:</p>
                            <p>{{ $comentario->comentario }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <div>
                <a href="{{ route('capitulos.index', ["id" => $capitulo->historia_id]) }}">
                    Volver a la Historia
                </a>

                @auth
                    @if ($capitulo->historia && Auth::id() == $capitulo->historia->user_id)
                        <div>
                            <a href="{{ route('capitulos.edit', $capitulo->id) }}">
                                Editar Capítulo
                            </a>
                            <form action="{{ route('capitulos.destroy', $capitulo->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este capítulo? Esta acción es irreversible.');">
                                    Eliminar Capítulo
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection