<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $productos = \App\Models\Producto::where('visible', true)->latest()->take(12)->get();
    return view('home', compact('productos'));
});

Route::get('/productos', function () {
    $query = \App\Models\Producto::query();
    if ($q = request('q')) {
        $query->where('nombre', 'like', "%{$q}%")->orWhere('descripcion', 'like', "%{$q}%");
    }
    $productos = $query->paginate(12);
    return view('productos.index', compact('productos'));
});

Route::get('/productos/{producto}', function (\App\Models\Producto $producto) {
    return view('productos.show', compact('producto'));
})->name('productos.show');

