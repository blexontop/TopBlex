@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @php
                $images = \Illuminate\Support\Facades\DB::table('imagen_productos')->where('producto_id', $producto->id)->orderBy('orden')->get();
            @endphp
            <div class="space-y-2">
                @if($images->isNotEmpty())
                        <img src="{{ $images->first()->url }}" alt="{{ $producto->nombre }}" class="product-main-img" />
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            @foreach($images as $img)
                                <img src="{{ $img->url }}" data-src="{{ $img->url }}" class="product-thumb" />
                            @endforeach
                        </div>
                @else
                    <img src="https://via.placeholder.com/800x600?text=Producto" alt="{{ $producto->nombre }}" class="w-full h-96 object-cover rounded" />
                @endif
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-bold">{{ $producto->nombre }}</h1>
            <div class="text-xl font-semibold mt-2">{{ $producto->precio ? '$'.number_format($producto->precio, 2) : '—' }}</div>
            <p class="text-gray-700 mt-4">{!! nl2br(e($producto->descripcion)) !!}</p>

            <form action="{{ url('/carrito/agregar') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <button class="bg-black text-white px-6 py-2 rounded">Añadir al carrito</button>
            </form>
        </div>
    </div>
@endsection
