<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeToTopblexMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Controlador de autenticación: registro, inicio y cierre de sesión.
class AuthController extends Controller
{
    // Muestra el formulario de login.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el login: valida las credenciales e inicia sesión.
    public function attemptLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        // Auth::attempt busca el email y compara la contraseña con su hash guardado.
        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Credenciales invalidas.',
            ]);
        }

        // Regenera la sesión tras el login (seguridad: evita el robo de sesión).
        $request->session()->regenerate();

        // Si es administrador va al panel; si no, a su cuenta.
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('account.index'));
    }

    // Muestra el formulario de registro.
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesa el registro de un nuevo usuario.
    public function register(Request $request)
    {
        // 1) Valida los datos (email único, contraseña de 8+ caracteres y confirmada).
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2) Crea el usuario. La contraseña se guarda cifrada con Hash::make, nunca en texto plano.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
        ]);

        // 3) Envía el correo de bienvenida. Si el correo falla, NO rompe el registro.
        try {
            Mail::to($user->email)->send(new WelcomeToTopblexMail($user));
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el correo de bienvenida: ' . $e->getMessage());
        }

        // 4) Inicia sesión automáticamente y regenera la sesión por seguridad.
        Auth::login($user);
        $request->session()->regenerate();

        $message = 'Hola, ' . $user->name . '. Tu cuenta se creo correctamente.';

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', $message);
        }

        return redirect()->route('account.index')->with('success', $message);
    }

    // Cierra la sesión del usuario, la invalida y vuelve al inicio.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
