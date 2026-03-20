@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Productos</h1>
        <div class="toolbar">
            <div class="section-muted">Mostrando {{ $productos->count() }} de {{ $productos->total() }}</div>
            <form method="GET" action="{{ route('productos.index') }}" class="toolbar">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="genero" value="{{ $generoSeleccionado }}">
                <input type="hidden" name="tipo" value="{{ $tipoSeleccionado }}">
                <select name="sort" onchange="this.form.submit()" class="filter-select">
                    <option value="latest" @selected(request('sort', 'latest') === 'latest')>Mas recientes</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Precio: menor a mayor</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Precio: mayor a menor</option>
                </select>
            </form>
        </div>
    </div>

    <div class="filters-wrap">
        <a
            href="{{ route('productos.index', array_filter(['q' => request('q'), 'sort' => request('sort', 'latest')])) }}"
            class="filter-chip {{ empty($generoSeleccionado) ? 'is-active' : '' }}"
        >
            Todo
        </a>

        @foreach($generos as $genero)
            <a
                href="{{ route('productos.index', array_filter(['q' => request('q'), 'sort' => request('sort', 'latest'), 'genero' => $genero->slug])) }}"
                class="filter-chip {{ $generoSeleccionado === $genero->slug ? 'is-active' : '' }}"
            >
                {{ $genero->nombre }}
            </a>
        @endforeach
    </div>

    @if(!empty($generoSeleccionado) && $tiposDisponibles->isNotEmpty())
        <div class="filters-wrap">
            <a
                href="{{ route('productos.index', array_filter(['q' => request('q'), 'sort' => request('sort', 'latest'), 'genero' => $generoSeleccionado])) }}"
                class="filter-chip {{ empty($tipoSeleccionado) ? 'is-active' : '' }}"
            >
                Todos los tipos
            </a>

            @foreach($tiposDisponibles as $tipo)
                <a
                    href="{{ route('productos.index', array_filter(['q' => request('q'), 'sort' => request('sort', 'latest'), 'genero' => $generoSeleccionado, 'tipo' => $tipo->slug])) }}"
                    class="filter-chip {{ $tipoSeleccionado === $tipo->slug ? 'is-active' : '' }}"
                >
                    {{ $tipo->nombre }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="product-grid">
        @foreach($productos as $producto)
            @include('components.product-card', ['producto' => $producto])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $productos->links() }}
    </div>
@endsection
