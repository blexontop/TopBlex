@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Productos</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($productos as $producto)
            @include('components.product-card', ['producto' => $producto])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $productos->links() }}
    </div>
@endsection
