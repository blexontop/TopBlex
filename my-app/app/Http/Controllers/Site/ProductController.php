<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('products') || !Schema::hasTable('categories')) {
            return view('products.index', [
                'products' => collect(),
                'generos' => collect(),
                'tiposDisponibles' => collect(),
                'generoSeleccionado' => 'all',
                'tipoSeleccionado' => null,
            ]);
        }

        $query = Product::query()->with('category');

        $generoSeleccionado = request('genero', 'all');
        $tipoSeleccionado = request('tipo');

        $generos = Category::query()
            ->whereNull('parent_id')
            ->whereIn('slug', ['hombre', 'mujer'])
            ->where('is_active', true)
            ->with(['children' => function ($q) {
                $q->where('is_active', true)
                  ->orderBy('sort_order')
                  ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if (in_array($generoSeleccionado, ['hombre', 'mujer'], true)) {
            $query->whereHas('category', function ($q) use ($generoSeleccionado) {
                $q->where('slug', $generoSeleccionado)
                  ->orWhereHas('parent', function ($p) use ($generoSeleccionado) {
                      $p->where('slug', $generoSeleccionado);
                  });
            });
        }

        if ($tipoSeleccionado) {
            $query->whereHas('category', function ($q) use ($tipoSeleccionado) {
                $q->where('slug', $tipoSeleccionado);
            });
        }

        if ($q = request('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $sort = request('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query
            ->where('is_visible', true)
            ->paginate(12)
            ->appends(request()->query());

        $tiposDisponibles = collect();
        if (in_array($generoSeleccionado, ['hombre', 'mujer'], true)) {
            $tiposDisponibles = $generos
                ->firstWhere('slug', $generoSeleccionado)?->children ?? collect();
        }

        return view('products.index', compact(
            'products',
            'generos',
            'tiposDisponibles',
            'generoSeleccionado',
            'tipoSeleccionado'
        ));
    }

    public function show(Product $product)
    {
        return view('products.show', ['producto' => $product]);
    }

    public function categories(): RedirectResponse
    {
        return redirect()->route('products.index');
    }

    public function collections(): RedirectResponse
    {
        return redirect()->route('products.index');
    }
}
