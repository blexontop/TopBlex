@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @php
                $images = \Illuminate\Support\Facades\DB::table('product_images')->where('product_id', $producto->id)->orderBy('sort_order')->get();
                $mainImage = $images->first()?->url;
                $fallbacks = [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80',
                ];
                $fallback = $fallbacks[$producto->id % count($fallbacks)];
            @endphp
            <div class="space-y-2">
                <img src="{{ $mainImage ?? $fallback }}" alt="{{ $producto->name }}" class="product-main-img max-w-sm mx-auto" />
            </div>
        </div>
        <div>
            <h1 class="product-detail-title">{{ $producto->name }}</h1>
            <div class="product-detail-price">{{ $producto->price ? '$'.number_format($producto->price, 2) : '-' }}</div>
            <p class="product-detail-description">{!! nl2br(e($producto->description)) !!}</p>

            <form action="{{ route('cart.add') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $producto->id }}">
                <button class="btn-primary" type="submit">Anadir al carrito</button>
            </form>
        </div>
    </div>
@endsection
