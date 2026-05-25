@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Success Icon -->
        <div class="flex justify-center">
            <div class="rounded-full bg-green-100 p-3" style="background: rgba(34, 197, 94, 0.1);">
                <svg class="w-12 h-12" style="color: #22c55e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="text-center">
            <h1 class="text-3xl font-bold" style="color: var(--syna-text);">¡Pago exitoso!</h1>
            <p class="mt-2" style="color: var(--syna-muted);">Tu pedido ha sido confirmado</p>
        </div>

        <!-- Order Details -->
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem; space-y: 1rem;">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4" style="border-bottom: 1px solid var(--syna-line);">
                    <span style="color: var(--syna-muted);">Código del pedido</span>
                    <span class="font-mono font-bold" style="color: var(--syna-text); font-size: 1.1rem;">{{ $order->code }}</span>
                </div>

                <div class="flex justify-between items-center pb-4" style="border-bottom: 1px solid var(--syna-line);">
                    <span style="color: var(--syna-muted);">Monto</span>
                    <span class="font-bold" style="color: var(--syna-text); font-size: 1.2rem;">€{{ number_format($order->total, 2, ',', '.') }}</span>
                </div>

                <div class="flex justify-between items-center pb-4" style="border-bottom: 1px solid var(--syna-line);">
                    <span style="color: var(--syna-muted);">Método de pago</span>
                    <span style="color: var(--syna-text);">Tarjeta de crédito (Stripe)</span>
                </div>

                <div class="flex justify-between items-center">
                    <span style="color: var(--syna-muted);">Estado</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">Pagado</span>
                </div>
            </div>
        </div>

        <!-- Items Summary -->
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <h3 class="font-bold mb-4" style="color: var(--syna-text);">Artículos</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold" style="color: var(--syna-text);">{{ $item->product_name }}</p>
                            <p class="text-sm" style="color: var(--syna-muted);">Cantidad: {{ $item->quantity }}</p>
                        </div>
                        <span class="font-bold" style="color: var(--syna-text);">€{{ number_format($item->subtotal, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping Info -->
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <h3 class="font-bold mb-4" style="color: var(--syna-text);">Dirección de envío</h3>
            <p style="color: var(--syna-muted); line-height: 1.6;">
                {{ $order->shipping_address ?: 'No especificada' }}
            </p>
        </div>

        <!-- Email Confirmation Note -->
        <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 8px; padding: 1rem;">
            <p class="text-sm" style="color: var(--syna-text);">
                ✓ Se ha enviado un correo de confirmación a <span class="font-semibold">{{ $order->user->email }}</span>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 pt-4">
            <a href="{{ route('account.index') }}" class="topbar-btn-solid text-center">
                Ver mi cuenta
            </a>
            <a href="{{ route('home') }}" class="topbar-btn-ghost text-center">
                Continuar comprando
            </a>
        </div>

        <!-- Support Text -->
        <p class="text-center text-sm" style="color: var(--syna-muted);">
            ¿Problemas? <a href="{{ route('contact.index') }}" class="font-semibold hover:underline" style="color: var(--syna-text);">Contacta con soporte</a>
        </p>
    </div>
</div>
@endsection
