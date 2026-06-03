<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
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

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'size' => ['required', 'string', 'in:XS,S,M,L,XL'],
        ]);

        $producto = Product::find($validated['product_id']);
        if (!$producto) {
            return back()->with('success', 'Product no encontrado.');
        }

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
