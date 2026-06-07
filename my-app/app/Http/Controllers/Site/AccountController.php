<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('account.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    $normalized = strtolower(trim($value));
                    $currentNormalized = strtolower(trim($user->email));
                    
                    // Only check if email changed
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
        ]);

        $data['email'] = strtolower(trim($data['email']));

        $user->update($data);

        return back()->with('success', 'Tu informacion se guardo correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['password' => 'La contraseña actual no coincide.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        Mail::to($user->email)->send(new PasswordChangedMail($user));

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
