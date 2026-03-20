@php
    $img = \Illuminate\Support\Facades\DB::table('imagen_productos')->where('producto_id', $producto->id)->orderBy('orden')->first();
    $fallbacks = [
        'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
    ];
    $fallback = $fallbacks[$producto->id % count($fallbacks)];
@endphp

<div class="product-card-container">
    <a href="{{ route('productos.show', $producto) }}" class="block">
        <div class="image-wrap">
            <img src="{{ $img?->url ?? $fallback }}" alt="{{ $producto->nombre }}" class="product-card-image" />
            <div class="image-overlay">
                <span class="btn-ghost">Ver</span>
            </div>
            @if(!empty($producto->destacado) && $producto->destacado)
                <div class="product-badge">Destacado</div>
            @endif
        </div>
    </a>
    <div class="product-card-body">
        <h3 class="product-name">{{ $producto->nombre }}</h3>
        <p class="product-description">{{ \Illuminate\Support\Str::limit($producto->descripcion, 80) }}</p>
        <div class="mt-3 flex items-center justify-between">
            <div>
                @if(isset($producto->precio_old) && $producto->precio_old)
                    <span class="price-old">${{ number_format($producto->precio_old, 2) }}</span>
                @endif
                <span class="price-current">{{ $producto->precio ? '$'.number_format($producto->precio, 2) : '—' }}</span>
            </div>
            <form action="{{ route('carrito.agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <button class="btn-primary" type="submit">Anadir</button>
            </form>
        </div>
    </div>
</div>
