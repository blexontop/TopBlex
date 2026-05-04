@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Pago seguro con Stripe</h1>
        <div class="section-muted">Revisa el resumen antes de pagar.</div>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="product-grid">
        @foreach($items as $item)
            <article class="product-card-container">
                <div class="product-card-body">
                    <h2 class="product-name">{{ $item['name'] ?? 'Producto' }}</h2>
                    <p class="product-description">Cantidad: {{ (int) ($item['quantity'] ?? 1) }}</p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="price-current">${{ number_format((float) ($item['price'] ?? 0), 2) }}</span>
                        <span class="section-muted">Subtotal: ${{ number_format(((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 1)), 2) }}</span>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6 flex items-center justify-between">
        <div class="section-muted">Total a pagar</div>
        <div class="product-detail-price">${{ number_format($total, 2) }}</div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('cart.index') }}" class="btn-ghost">Volver al carrito</a>
        <form action="{{ route('stripe.checkout.session') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary">Pagar en Stripe</button>
        </form>
    </div>
@endsection