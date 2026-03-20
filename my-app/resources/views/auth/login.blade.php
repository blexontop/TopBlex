@extends('layouts.app')

@section('content')
    <div class="auth-wrap">
        <div class="section-header">
            <h1 class="section-title">Iniciar sesion</h1>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="panel-card">
            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="input-base" value="{{ old('email') }}" required autocomplete="email" />
                </div>

                <div>
                    <label for="password" class="form-label">Contrasena</label>
                    <input id="password" type="password" name="password" class="input-base" required autocomplete="current-password" />
                </div>

                <label class="inline-flex items-center gap-2 text-sm section-muted">
                    <input type="checkbox" name="remember" value="1" />
                    Recordarme
                </label>

                <div class="flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="btn-primary">Entrar</button>
                    <a href="{{ route('register') }}" class="btn-ghost">Crear cuenta</a>
                </div>
            </form>
        </div>
    </div>
@endsection
