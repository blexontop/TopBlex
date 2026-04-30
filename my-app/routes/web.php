<?php

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Faq;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Route::get('/', function () {
    $products = collect();

    if (Schema::hasTable('products')) {
        try {
            $products = Product::where('is_visible', true)->latest()->take(12)->get();
        } catch (QueryException) {
            $products = collect();
        }
    }

    return view('home', compact('products'));
})->name('home');

Route::get('/products', function () {
    if (!Schema::hasTable('products') || !Schema::hasTable('categories')) {
        return view('products.index', [
            'products' => collect(),
            'generos' => collect(),
            'tiposDisponibles' => collect(),
            'generoSeleccionado' => null,
            'tipoSeleccionado' => null,
        ]);
    }

    $query = Product::query()->with('category');

    $generoSeleccionado = request('genero');
    $tipoSeleccionado = request('tipo');

    $generos = Category::query()
        ->whereNull('parent_id')
        ->whereIn('slug', ['hombre', 'mujer'])
        ->where('is_active', true)
        ->with(['children' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
        }])
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    if ($generoSeleccionado) {
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
    if ($generoSeleccionado) {
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
})->name('products.index');

Route::get('/categories', function () {
    return redirect()->route('products.index');
})->name('categories.index');

Route::get('/collections', function () {
    return redirect()->route('products.index');
})->name('collections.index');

Route::get('/products/{product}', function (\App\Models\Product $product) {
    return view('products.show', ['producto' => $product]);
})->name('products.show');

Route::get('/cart', function (Request $request) {
    $cart = collect($request->session()->get('cart', []));
    $total = $cart->sum(function (array $item) {
        return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
    });

    return view('cart.index', [
        'items' => $cart->values(),
        'total' => $total,
    ]);
})->name('cart.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Credenciales invalidas.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('account.index'));
    })->name('login.attempt');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account.index')->with('success', 'Hola, ' . $user->name . '. Tu cuenta se creo correctamente.');
    })->name('register.store');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', function (Request $request) {
        $user = $request->user();

        return view('account.index', compact('user'));
    })->name('account.index');

    Route::put('/account', function (Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Tu informacion se guardo correctamente.');
    })->name('account.update');

    Route::post('/orders/confirm', function (Request $request) {
        $cart = collect($request->session()->get('cart', []));
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Tu carrito esta vacio.');
        }

        $user = $request->user();

        $pedido = DB::transaction(function () use ($cart, $user) {
            $total = (float) $cart->sum(function (array $item) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            });

            $code = 'TBX-' . Str::upper(Str::random(8));

            $pedido = Order::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 'pending',
                'total' => $total,
                'currency' => 'EUR',
                'shipping_address' => trim(($user->address ?? '') . ' ' . ($user->city ?? '')),
            ]);

            foreach ($cart as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);

                OrderItem::create([
                    'order_id' => $pedido->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => (string) ($item['name'] ?? 'Product'),
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }

            Payment::create([
                'order_id' => $pedido->id,
                'method' => 'pending',
                'status' => 'pending',
                'reference' => 'REF-' . Str::upper(Str::random(10)),
                'amount' => $total,
            ]);

            return $pedido;
        });

        $request->session()->forget('cart');

        return redirect()->route('account.index')->with('success', 'Order ' . $pedido->code . ' realizado con exito.');
    })->name('orders.confirm');

    Route::get('/orders', function (Request $request) {
        $user = $request->user();

        $orders = Order::query()
            ->with(['items'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    })->name('orders.index');
});

Route::get('/contact', function () {
    return view('contact.index');
})->name('contact.index');

Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:180'],
        'subject' => ['required', 'string', 'max:180'],
        'message' => ['required', 'string', 'max:3000'],
    ]);

    DB::table('contact_messages')->insert([
        'user_id' => Auth::id(),
        'name' => $data['name'],
        'email' => $data['email'],
        'subject' => $data['subject'],
        'message' => $data['message'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Mensaje enviado correctamente.');
})->name('contact.store');

Route::get('/faqs', function () {
    $preguntas = Faq::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderByDesc('id')
        ->get();

    return view('faqs.index', compact('preguntas'));
})->name('faqs.index');

Route::post('/cart/add', function (Request $request) {
    $validated = $request->validate([
        'product_id' => ['required', 'integer'],
    ]);

    $producto = \App\Models\Product::find($validated['product_id']);
    if (!$producto) {
        return back()->with('success', 'Product no encontrado.');
    }

    $cart = $request->session()->get('cart', []);
    $key = (string) $producto->id;

    if (isset($cart[$key])) {
        $cart[$key]['quantity']++;
    } else {
        $cart[$key] = [
            'id' => $producto->id,
            'name' => $producto->name,
            'price' => (float) ($producto->price ?? 0),
            'quantity' => 1,
        ];
    }

    $request->session()->put('cart', $cart);

    return back()->with('success', 'Product anadido al carrito.');
})->name('cart.add');

