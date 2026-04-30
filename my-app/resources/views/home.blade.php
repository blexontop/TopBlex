@extends('layouts.app')

@section('hero')
    <section class="hero-full" style="background-image:url('https://images.unsplash.com/photo-1520975911559-0182c8e2f5c6?q=80&w=1600&auto=format&fit=crop');">
        <div class="hero-overlay"></div>
        <div class="container mx-auto px-4 py-28 relative">
            <div class="md:w-2/5 hero-content">
                <h1 class="hero-title">TopBlex</h1>
                <p class="mt-4 text-gray-100">Colecciones seleccionadas, materiales de calidad y envío rápido. Descubre lo último en nuestra tienda.</p>
                <div class="mt-6">
                    <a href="{{ url('/products') }}" class="btn-primary">Ver productos</a>
                    <a href="{{ route('collections.index') }}" class="btn-ghost ml-3">Colecciones</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Productos destacados</h2>
        <div class="product-grid">
            @foreach($products as $producto)
                @include('components.product-card', ['producto' => $producto])
            @endforeach
        </div>
    </section>
@endsection
