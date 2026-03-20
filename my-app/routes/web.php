<?php

use App\Models\Categoria;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\PreguntaFrecuente;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    $productos = Producto::where('visible', true)->latest()->take(12)->get();
    return view('home', compact('productos'));
})->name('home');

Route::get('/productos', function () {
    $query = Producto::query()->with('categoria');

    $generoSeleccionado = request('genero');
    $tipoSeleccionado = request('tipo');

    $generos = Categoria::query()
        ->whereNull('parent_id')
        ->whereIn('slug', ['hombre', 'mujer'])
        ->where('activo', true)
        ->with(['children' => function ($q) {
            $q->where('activo', true)->orderBy('orden')->orderBy('nombre');
        }])
        ->orderBy('orden')
        ->orderBy('nombre')
        ->get();

    if ($generoSeleccionado) {
        $query->whereHas('categoria', function ($q) use ($generoSeleccionado) {
            $q->where('slug', $generoSeleccionado)
              ->orWhereHas('parent', function ($p) use ($generoSeleccionado) {
                  $p->where('slug', $generoSeleccionado);
              });
        });
    }

    if ($tipoSeleccionado) {
        $query->whereHas('categoria', function ($q) use ($tipoSeleccionado) {
            $q->where('slug', $tipoSeleccionado);
        });
    }

    if ($q = request('q')) {
        $query->where(function ($sq) use ($q) {
            $sq->where('nombre', 'like', "%{$q}%")
               ->orWhere('descripcion', 'like', "%{$q}%");
        });
    }

    $sort = request('sort', 'latest');
    if ($sort === 'price_asc') {
        $query->orderBy('precio', 'asc');
    } elseif ($sort === 'price_desc') {
        $query->orderBy('precio', 'desc');
    } else {
        $query->latest();
    }

    $productos = $query
        ->where('visible', true)
        ->paginate(12)
        ->appends(request()->query());

    $tiposDisponibles = collect();
    if ($generoSeleccionado) {
        $tiposDisponibles = $generos
            ->firstWhere('slug', $generoSeleccionado)?->children ?? collect();
    }

    return view('productos.index', compact(
        'productos',
        'generos',
        'tiposDisponibles',
        'generoSeleccionado',
        'tipoSeleccionado'
    ));
})->name('productos.index');

Route::get('/categorias', function () {
    return redirect()->route('productos.index');
})->name('categorias.index');

Route::get('/colecciones', function () {
    return redirect()->route('productos.index');
})->name('colecciones.index');

Route::get('/productos/{producto}', function (\App\Models\Producto $producto) {
    return view('productos.show', compact('producto'));
})->name('productos.show');

Route::get('/carrito', function (Request $request) {
    $cart = collect($request->session()->get('cart', []));
    $total = $cart->sum(function (array $item) {
        return ($item['precio'] ?? 0) * ($item['cantidad'] ?? 1);
    });

    return view('carrito.index', [
        'items' => $cart->values(),
        'total' => $total,
    ]);
})->name('carrito.index');

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

        return redirect()->intended(route('cuenta'));
    })->name('login.attempt');

    Route::get('/registro', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/registro', function (Request $request) {
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

        return redirect()->route('cuenta')->with('success', 'Cuenta creada correctamente.');
    })->name('register.store');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mi-cuenta', function (Request $request) {
        $user = $request->user();

        $pedidos = Pedido::query()
            ->with(['items'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $statsCuenta = [
            'pedidos_total' => $pedidos->count(),
            'pedidos_pendientes' => $pedidos->where('estado', 'pendiente')->count(),
            'total_gastado' => (float) $pedidos->sum('total'),
            'ultimo_pedido' => $pedidos->first(),
            'mensajes_enviados' => DB::table('mensaje_contactos')->where('user_id', $user->id)->count(),
        ];

        return view('cuenta.index', [
            'user' => $user,
            'pedidos' => $pedidos,
            'statsCuenta' => $statsCuenta,
        ]);
    })->name('cuenta');

    Route::put('/mi-cuenta', function (Request $request) {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telefono' => ['nullable', 'string', 'max:50'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Tu informacion se guardo correctamente.');
    })->name('cuenta.update');

    Route::post('/pedidos/confirmar', function (Request $request) {
        $cart = collect($request->session()->get('cart', []));
        if ($cart->isEmpty()) {
            return redirect()->route('carrito.index')->with('success', 'Tu carrito esta vacio.');
        }

        $user = $request->user();

        $pedido = DB::transaction(function () use ($cart, $user) {
            $total = (float) $cart->sum(function (array $item) {
                return ($item['precio'] ?? 0) * ($item['cantidad'] ?? 1);
            });

            $codigo = 'TBX-' . Str::upper(Str::random(8));

            $pedido = Pedido::create([
                'user_id' => $user->id,
                'codigo' => $codigo,
                'estado' => 'pendiente',
                'total' => $total,
                'moneda' => 'EUR',
                'direccion_envio' => trim(($user->direccion ?? '') . ' ' . ($user->ciudad ?? '')),
            ]);

            foreach ($cart as $item) {
                $cantidad = (int) ($item['cantidad'] ?? 1);
                $precio = (float) ($item['precio'] ?? 0);

                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'] ?? null,
                    'nombre_producto' => (string) ($item['nombre'] ?? 'Producto'),
                    'precio_unitario' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $precio * $cantidad,
                ]);
            }

            Pago::create([
                'pedido_id' => $pedido->id,
                'metodo' => 'pendiente',
                'estado' => 'pendiente',
                'referencia' => 'REF-' . Str::upper(Str::random(10)),
                'monto' => $total,
            ]);

            return $pedido;
        });

        $request->session()->forget('cart');

        return redirect()->route('cuenta')->with('success', 'Pedido ' . $pedido->codigo . ' realizado con exito.');
    })->name('pedidos.confirmar');

    Route::get('/pedidos', function () {
        return redirect()->to(route('cuenta') . '#pedidos');
    })->name('pedidos');
});

Route::get('/contacto', function () {
    return view('contacto.index');
})->name('contacto');

Route::post('/contacto', function (Request $request) {
    $data = $request->validate([
        'nombre' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:180'],
        'asunto' => ['required', 'string', 'max:180'],
        'mensaje' => ['required', 'string', 'max:3000'],
    ]);

    DB::table('mensaje_contactos')->insert([
        'user_id' => Auth::id(),
        'nombre' => $data['nombre'],
        'email' => $data['email'],
        'asunto' => $data['asunto'],
        'mensaje' => $data['mensaje'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Mensaje enviado correctamente.');
})->name('contacto.store');

Route::get('/preguntas-frecuentes', function () {
    $preguntas = PreguntaFrecuente::query()
        ->where('activo', true)
        ->orderBy('orden')
        ->orderByDesc('id')
        ->get();

    return view('faq.index', compact('preguntas'));
})->name('faq');

Route::post('/carrito/agregar', function (Request $request) {
    $validated = $request->validate([
        'producto_id' => ['required', 'integer'],
    ]);

    $producto = \App\Models\Producto::find($validated['producto_id']);
    if (!$producto) {
        return back()->with('success', 'Producto no encontrado.');
    }

    $cart = $request->session()->get('cart', []);
    $key = (string) $producto->id;

    if (isset($cart[$key])) {
        $cart[$key]['cantidad']++;
    } else {
        $cart[$key] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => (float) ($producto->precio ?? 0),
            'cantidad' => 1,
        ];
    }

    $request->session()->put('cart', $cart);

    return back()->with('success', 'Producto anadido al carrito.');
})->name('carrito.agregar');

