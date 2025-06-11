@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('style.css') }}">

<div class="capitulos-container">
    <h1>Todos los Capítulos</h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @php
        $historiasAgrupadas = [];
    @endphp

    @foreach($capitulos as $capitulo)
        @php
            $historia = $capitulo->historia;
        @endphp

        @if ($historia && !isset($historiasAgrupadas[$historia->id]))
            @php
                $capitulosDeHistoria = $capitulos->filter(function ($c) use ($historia) {
                    return $c->historia_id === $historia->id;
                });
                $historiasAgrupadas[$historia->id] = true;
            @endphp

            <div class="historia-bloque">
                <h2>
                    Título: <a href="{{ route('historias.show', $historia->id) }}">
                        {{ $historia->titulo }}
                    </a>
                </h2>

                <ul>
                    @foreach($capitulosDeHistoria as $c)
                        <li>
                            <a href="{{ route('capitulos.show', $c->id) }}">
                                {{ $c->titulo }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach

    <div class="paginacion">
        {{ $capitulos->links() }}
    </div>

    <a href="{{ route('inicio') }}" class="volver-inicio">
        Volver a la Página Principal
    </a>
</div>
@endsection
