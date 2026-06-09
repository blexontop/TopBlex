<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

// Controlador del carrito: guarda los productos en la sesión (todavía no en la base de datos).
class CartController extends Controller
{
    // Muestra el carrito y calcula el total.
    public function index(Request $request)
    {
        $cart = collect($request->session()->get('cart', []));
        $total = $cart->sum(function (array $item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return view('cart.index', [
            'items' => $cart->values(),
            'total' => $total,
        ]);
    }

    // Añade un producto al carrito. La talla es OBLIGATORIA.
    public function add(Request $request)
    {
        // Valida que se haya elegido una talla válida (XS, S, M, L o XL).
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'size' => ['required', 'string', 'in:XS,S,M,L,XL'],
        ], [
            'size.required' => 'Debes seleccionar una talla antes de anadir el producto al carrito.',
            'size.in' => 'La talla seleccionada no es valida.',
            'product_id.required' => 'No se ha indicado el producto.',
        ]);

        $producto = Product::find($validated['product_id']);
        if (!$producto) {
            return back()->withErrors(['product_id' => 'Producto no encontrado.']);
        }

        // La clave combina producto + talla: así "camiseta M" y "camiseta L" son líneas distintas.
        $cart = $request->session()->get('cart', []);
        $key = $producto->id . '_' . $validated['size'];

        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
        } else {
            $cart[$key] = [
                'id' => $producto->id,
                'name' => $producto->name,
                'price' => (float) ($producto->price ?? 0),
                'size' => $validated['size'],
                'quantity' => 1,
            ];
        }

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Product anadido al carrito.');
    }
}
