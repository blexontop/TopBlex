@component('mail::message')
# ¡Pedido Confirmado!

Hola **{{ $user->name }}**,

Tu pedido ha sido confirmado exitosamente. Aquí están los detalles:

**Código del pedido:** {{ $order->code }}
**Estado:** Pagado
**Fecha:** {{ $order->created_at->format('d/m/Y H:i') }}

## Detalles del pedido

| Producto | Cantidad | Precio Unitario | Subtotal |
|----------|----------|-----------------|----------|
@foreach($items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | €{{ number_format($item->unit_price, 2, ',', '.') }} | €{{ number_format($item->subtotal, 2, ',', '.') }} |
@endforeach

**Total:** €{{ number_format($total, 2, ',', '.') }}

## Información de envío

**Dirección:** {{ $order->shipping_address }}

Tu pedido será enviado próximamente. Te notificaremos cuando esté en camino.

---

Si tienes alguna pregunta, no dudes en contactarnos.

@component('mail::button', ['url' => route('account.index')])
Ver mi cuenta
@endcomponent

Gracias por comprar en **TopBlex**.

@endcomponent
