@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Cart</h1>
        <div class="section-muted">{{ $items->sum('quantity') }} articulos</div>
    </div>

    @if($items->isEmpty())
        <p class="section-muted">Tu carrito esta vacio.</p>
        <a href="{{ route('products.index') }}" class="btn-ghost mt-4">Ir a products</a>
    @else
        <div class="product-grid">
            @foreach($items as $item)
                <article class="product-card-container">
                    <div class="product-card-body">
                        <h2 class="product-name">{{ $item['name'] }}</h2>
                        <p class="product-description">Cantidad: {{ $item['quantity'] }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="price-current">${{ number_format($item['price'], 2) }}</span>
                            <span class="section-muted">Subtotal: ${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="section-muted">Total</div>
            <div class="product-detail-price">${{ number_format($total, 2) }}</div>
        </div>

        <div class="mt-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index') }}" class="btn-ghost">Seguir comprando</a>

                @auth
                    <a href="{{ route('stripe.checkout.show') }}" class="btn-primary">Finalizar pedido</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Inicia sesion para comprar</a>
                @endauth
            </div>
        </div>
    @endif
@endsection
