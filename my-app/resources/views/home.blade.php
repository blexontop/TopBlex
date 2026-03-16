@extends('layouts.app')

@section('content')
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
            <h1 class="text-4xl font-extrabold mb-4">TopBlex — Moda que inspira</h1>
            <p class="text-gray-700 mb-6">Colecciones seleccionadas, materiales de calidad y envío rápido. Descubre lo último en nuestra tienda.</p>
            <a href="{{ url('/productos') }}" class="inline-block bg-black text-white px-6 py-3 rounded">Ver productos</a>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1520975911559-0182c8e2f5c6?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=placeholder" alt="Moda" class="w-full rounded shadow" />
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Productos destacados</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($productos as $producto)
                @include('components.product-card', ['producto' => $producto])
            @endforeach
        </div>
    </section>
@endsection
