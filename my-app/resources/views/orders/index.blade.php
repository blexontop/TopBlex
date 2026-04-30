@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Pedidos</h1>
        <div class="section-muted">Historial de compras realizadas</div>
    </div>

    <div class="panel-card mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('account.index') }}" class="btn-ghost">Mi cuenta</a>
            <a href="{{ route('contact.index') }}" class="btn-ghost">Contactar soporte</a>
            <a href="{{ route('faqs.index') }}" class="btn-ghost">Preguntas frecuentes</a>
            <a href="{{ route('cart.index') }}" class="btn-ghost">Ir al carrito</a>
        </div>
    </div>

    <div class="panel-card">
        <h2 class="account-block-title">Pedidos realizados</h2>

        @if($orders->isEmpty())
            <p class="section-muted">Aun no tienes orders. Cuando finalices una compra, aparecera aqui.</p>
            <a href="{{ route('products.index') }}" class="btn-ghost mt-4">Ver products</a>
        @else
            <div class="grid gap-3">
                @foreach($orders as $pedido)
                    <article class="order-card">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                            <div>
                                <p class="form-label">Order</p>
                                <p class="order-code">{{ $pedido->code ?: ('PED-' . $pedido->id) }}</p>
                            </div>
                            <div>
                                <p class="form-label">Estado</p>
                                <p class="order-status">{{ strtoupper($pedido->status ?? 'pending') }}</p>
                            </div>
                            <div>
                                <p class="form-label">Fecha</p>
                                <p class="section-muted">{{ optional($pedido->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="form-label">Total</p>
                                <p class="price-current">${{ number_format((float) $pedido->total, 2) }}</p>
                            </div>
                        </div>

                        @if($pedido->items->isNotEmpty())
                            <div class="order-items">
                                @foreach($pedido->items as $item)
                                    <div class="order-item-row">
                                        <span>{{ $item->product_name }}</span>
                                        <span>x{{ $item->quantity }}</span>
                                        <span>${{ number_format((float) $item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection
