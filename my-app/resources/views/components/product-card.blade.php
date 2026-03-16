@php
    $img = \Illuminate\Support\Facades\DB::table('imagen_productos')->where('producto_id', $producto->id)->orderBy('orden')->first();
@endphp

<div class="product-card-container">
    <a href="{{ route('productos.show', $producto) }}" class="block">
        <div class="image-wrap">
            <img src="{{ $img?->url ?? 'https://via.placeholder.com/400x300?text=Producto' }}" alt="{{ $producto->nombre }}" class="product-card-image" />
            <div class="image-overlay">
                <a href="{{ route('productos.show', $producto) }}" class="btn-ghost">Ver</a>
            </div>
            @if(!empty($producto->destacado) && $producto->destacado)
                <div class="product-badge">Destacado</div>
            @endif
        </div>
    </a>
    <div class="product-card-body">
        <h3 class="text-sm font-medium">{{ $producto->nombre }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($producto->descripcion, 80) }}</p>
        <div class="mt-3 flex items-center justify-between">
            <div>
                @if(isset($producto->precio_old) && $producto->precio_old)
                    <span class="price-old">${{ number_format($producto->precio_old, 2) }}</span>
                @endif
                <span class="price-current">{{ $producto->precio ? '$'.number_format($producto->precio, 2) : '—' }}</span>
            </div>
            <form action="{{ url('/carrito/agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <button class="btn-primary">Añadir</button>
            </form>
        </div>
    </div>
</div>
