<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

// CRUD de productos del administrador (Crear, Leer, Actualizar, Borrar y ajustar stock).
class ProductController extends Controller
{
    // Lista los productos con buscador (por nombre o SKU) y paginación de 15 en 15.
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with(['category'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.products.index', compact('products', 'search'));
    }

    // Muestra el formulario para crear un producto nuevo.
    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => Category::query()->orderBy('name')->get(),
            'collections' => Collection::query()->orderBy('name')->get(),
        ]);
    }

    // Valida y guarda el producto nuevo en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $product = new Product();
        $product->fill($this->payload($validated, $request));
        $product->slug = $this->makeUniqueSlug($validated['name']); // genera la URL amigable
        $product->save();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    // Muestra el formulario para editar un producto existente.
    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'collections' => Collection::query()->orderBy('name')->get(),
        ]);
    }

    // Valida y guarda los cambios de un producto existente.
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules($product));

        $product->fill($this->payload($validated, $request));

        if (empty($product->slug)) {
            $product->slug = $this->makeUniqueSlug($validated['name'], $product->id);
        }

        $product->save();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // Elimina un producto.
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // Suma o resta stock a un producto (nunca baja de 0).
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'adjustment' => ['required', 'integer'],
        ]);

        $newStock = max(0, (int) $product->stock + (int) $data['adjustment']);
        $product->update(['stock' => $newStock]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Stock ajustado correctamente.');
    }

    // Reglas de validación del formulario de producto (el SKU debe ser único).
    protected function rules(?Product $product = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'is_visible' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    // Prepara los datos ya validados para guardarlos en el producto.
    protected function payload(array $validated, Request $request): array
    {
        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'price' => $validated['price'],
            'sku' => $validated['sku'] ?? null,
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'] ?? null,
            'collection_id' => $validated['collection_id'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    // Crea un slug (URL amigable) único; si ya existe, le añade un número al final.
    protected function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'product';
        $slug = $base;
        $counter = 2;

        while (
            Product::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
