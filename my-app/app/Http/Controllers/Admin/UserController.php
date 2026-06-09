<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Gestión de usuarios del administrador: listar, ver ficha, editar y borrar usuarios.
class UserController extends Controller
{
    // Lista los usuarios con buscador (por nombre o email) y paginación.
    public function index(Request $request)
    {
        $query = User::query()->orderByDesc('id');

        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Muestra la ficha de un usuario con sus pedidos y pagos.
    public function show(User $user)
    {
        $orders = $user->orders()->with('items', 'payments')->orderByDesc('id')->get();

        return view('admin.users.show', compact('user', 'orders'));
    }

    // Muestra el formulario para editar un usuario.
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Valida y guarda los cambios del usuario (datos, contraseña y rol de admin).
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                // Comprueba que el email no esté repetido (solo si lo ha cambiado).
                function ($attribute, $value, $fail) use ($user) {
                    $normalized = strtolower(trim($value));
                    $currentNormalized = strtolower(trim($user->email));

                    if ($normalized !== $currentNormalized) {
                        $exists = User::whereRaw('LOWER(email) = ?', [$normalized])->exists();
                        if ($exists) {
                            $fail('The email has already been taken.');
                        }
                    }
                }
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        // Si no se escribe contraseña nueva, se mantiene la actual; si se escribe, se cifra.
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_admin'] = isset($data['is_admin']) ? (bool) $data['is_admin'] : false;

        $data['email'] = strtolower(trim($data['email']));

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', 'Usuario actualizado.');
    }

    // Elimina un usuario.
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
