<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$user = \App\Models\User::where('email', 'frede2000sall@gmail.com')->first();

if ($user) {
    echo "✓ Cuenta admin creada:\n";
    echo "  Email: " . $user->email . "\n";
    echo "  Nombre: " . $user->name . "\n";
    echo "  Es admin: " . ($user->is_admin ? 'Sí' : 'No') . "\n";
} else {
    echo "✗ Cuenta admin no encontrada\n";
}
