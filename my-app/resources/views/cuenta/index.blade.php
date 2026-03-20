@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Mi cuenta</h1>
        <div class="section-muted">Informacion personal y pedidos</div>
    </div>

    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <article class="panel-card">
            <p class="form-label">Pedidos totales</p>
            <p class="account-stat">{{ $statsCuenta['pedidos_total'] }}</p>
        </article>
        <article class="panel-card">
            <p class="form-label">Pendientes</p>
            <p class="account-stat">{{ $statsCuenta['pedidos_pendientes'] }}</p>
        </article>
        <article class="panel-card">
            <p class="form-label">Total gastado</p>
            <p class="account-stat">${{ number_format($statsCuenta['total_gastado'], 2) }}</p>
        </article>
        <article class="panel-card">
            <p class="form-label">Mensajes enviados</p>
            <p class="account-stat">{{ $statsCuenta['mensajes_enviados'] }}</p>
        </article>
    </div>

    <div class="panel-card mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('contacto') }}" class="btn-ghost">Contactar soporte</a>
            <a href="{{ route('faq') }}" class="btn-ghost">Preguntas frecuentes</a>
            <a href="{{ route('carrito.index') }}" class="btn-ghost">Ir al carrito</a>
        </div>
    </div>

    <div class="panel-card mb-8">
        <h2 class="account-block-title">Tu informacion</h2>
        <form action="{{ route('cuenta.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="name">Nombre</label>
                    <input id="name" type="text" name="name" class="input-base" value="{{ old('name', $user->name) }}" required />
                </div>

                <div>
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="input-base" value="{{ old('email', $user->email) }}" required />
                </div>

                <div>
                    <label class="form-label" for="telefono">Telefono</label>
                    <input id="telefono" type="text" name="telefono" class="input-base" value="{{ old('telefono', $user->telefono) }}" />
                </div>

                <div>
                    <label class="form-label" for="ciudad">Ciudad</label>
                    <input id="ciudad" type="text" name="ciudad" class="input-base" value="{{ old('ciudad', $user->ciudad) }}" />
                </div>
            </div>

            <div>
                <label class="form-label" for="direccion">Direccion</label>
                <input id="direccion" type="text" name="direccion" class="input-base" value="{{ old('direccion', $user->direccion) }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="password">Nueva contrasena (opcional)</label>
                    <input id="password" type="password" name="password" class="input-base" autocomplete="new-password" />
                </div>

                <div>
                    <label class="form-label" for="password_confirmation">Confirmar nueva contrasena</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="input-base" autocomplete="new-password" />
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary">Guardar informacion</button>
            </div>
        </form>
    </div>

    <div id="pedidos" class="panel-card">
        <h2 class="account-block-title">Pedidos realizados</h2>

        @if($pedidos->isEmpty())
            <p class="section-muted">Aun no tienes pedidos. Cuando finalices una compra, aparecera aqui.</p>
            <a href="{{ route('productos.index') }}" class="btn-ghost mt-4">Ver productos</a>
        @else
            <div class="grid gap-3">
                @foreach($pedidos as $pedido)
                    <article class="order-card">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                            <div>
                                <p class="form-label">Pedido</p>
                                <p class="order-code">{{ $pedido->codigo ?: ('PED-' . $pedido->id) }}</p>
                            </div>
                            <div>
                                <p class="form-label">Estado</p>
                                <p class="order-status">{{ strtoupper($pedido->estado ?? 'pendiente') }}</p>
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
                                        <span>{{ $item->nombre_producto }}</span>
                                        <span>x{{ $item->cantidad }}</span>
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
