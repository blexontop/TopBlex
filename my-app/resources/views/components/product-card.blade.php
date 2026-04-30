@php
    $img = \Illuminate\Support\Facades\DB::table('product_images')->where('product_id', $producto->id)->orderBy('sort_order')->first();
@endphp

<div class="product-card-container">
    <a href="{{ route('products.show', $producto) }}" class="block">
        <div class="image-wrap">
            <img src="{{ $img?->url ?? 'https://via.placeholder.com/400x300?text=Product' }}" alt="{{ $producto->name }}" class="product-card-image" />
            <div class="image-overlay">
                <a href="{{ route('products.show', $producto) }}" class="btn-ghost">Ver</a>
            </div>
            @if(!empty($producto->is_featured) && $producto->is_featured)
                <div class="product-badge">Destacado</div>
            @endif
        </div>
    </a>
    <div class="product-card-body">
        <h3 class="text-sm font-medium">{{ $producto->name }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($producto->description, 80) }}</p>
        <div class="mt-3 flex items-center justify-between">
            <div>
                @if(isset($producto->price_old) && $producto->price_old)
                    <span class="price-old">${{ number_format($producto->price_old, 2) }}</span>
                @endif
                <span class="price-current">{{ $producto->price ? '$'.number_format($producto->price, 2) : '—' }}</span>
            </div>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $producto->id }}">
                <button class="btn-primary">Añadir</button>
            </form>
        </div>
    </div>
</div>
