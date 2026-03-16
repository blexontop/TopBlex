@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Productos</h1>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-600">Mostrando {{ $productos->count() }} de {{ $productos->total() }}</div>
            <form method="GET" action="{{ url('/productos') }}">
                <select name="sort" onchange="this.form.submit()" class="border px-2 py-1 text-sm">
                    <option value="latest">Más recientes</option>
                    <option value="price_asc">Precio: menor a mayor</option>
                    <option value="price_desc">Precio: mayor a menor</option>
                </select>
            </form>
        </div>
    </div>

    <div class="product-grid">
        @foreach($productos as $producto)
            @include('components.product-card', ['producto' => $producto])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $productos->links() }}
    </div>
@endsection
