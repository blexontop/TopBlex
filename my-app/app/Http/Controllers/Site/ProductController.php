<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;

// Catálogo público de productos: listado con filtros y página de detalle.
class ProductController extends Controller
{
    // Lista los productos visibles, con filtros por género, tipo, búsqueda y orden.
    public function index()
    {
        // Si las tablas aún no existen, devuelve la vista vacía (evita errores al arrancar).
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

        // Categorías principales (hombre / mujer) con sus subcategorías activas.
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

        // Filtro por género (hombre/mujer).
        if (in_array($generoSeleccionado, ['hombre', 'mujer'], true)) {
            $query->whereHas('category', function ($q) use ($generoSeleccionado) {
                $q->where('slug', $generoSeleccionado)
                  ->orWhereHas('parent', function ($p) use ($generoSeleccionado) {
                      $p->where('slug', $generoSeleccionado);
                  });
            });
        }

        // Filtro por tipo de prenda.
        if ($tipoSeleccionado) {
            $query->whereHas('category', function ($q) use ($tipoSeleccionado) {
                $q->where('slug', $tipoSeleccionado);
            });
        }

        // Buscador por nombre o descripción.
        if ($q = request('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Ordenación: por precio (asc/desc) o por más recientes.
        $sort = request('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // Solo productos visibles, paginados de 12 en 12.
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

    // Muestra la página de detalle de un producto.
    public function show(Product $product)
    {
        return view('products.show', ['producto' => $product]);
    }

    // Redirecciones de compatibilidad hacia el listado de productos.
    public function categories(): RedirectResponse
    {
        return redirect()->route('products.index');
    }

    public function collections(): RedirectResponse
    {
        return redirect()->route('products.index');
    }
}
