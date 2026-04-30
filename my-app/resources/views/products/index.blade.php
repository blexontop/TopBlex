@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Productos</h1>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-600">Mostrando {{ $products->count() }} de {{ $products->total() }}</div>
            <form method="GET" action="{{ url('/products') }}" class="flex items-center gap-2">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <select name="genero" onchange="this.form.submit()" class="border px-2 py-1 text-sm">
                    <option value="all" {{ $generoSeleccionado === 'all' ? 'selected' : '' }}>Todas las categorias</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->slug }}" {{ $generoSeleccionado === $genero->slug ? 'selected' : '' }}>
                            {{ $genero->name }}
                        </option>
                    @endforeach
                </select>
                <select name="tipo" onchange="this.form.submit()" class="border px-2 py-1 text-sm" {{ $tiposDisponibles->isEmpty() ? 'disabled' : '' }}>
                    <option value="">Todos los tipos</option>
                    @foreach($tiposDisponibles as $tipo)
                        <option value="{{ $tipo->slug }}" {{ $tipoSeleccionado === $tipo->slug ? 'selected' : '' }}>
                            {{ $tipo->name }}
                        </option>
                    @endforeach
                </select>
                <select name="sort" onchange="this.form.submit()" class="border px-2 py-1 text-sm">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Más recientes</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                </select>
            </form>
        </div>
    </div>

    <div class="product-grid">
        @foreach($products as $producto)
            @include('components.product-card', ['producto' => $producto])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
