@extends('layouts.app')

@section('content')
    <div class="auth-wrap">
        <div class="section-header">
            <h1 class="section-title">Crear cuenta</h1>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="panel-card">
            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="form-label">Nombre</label>
                    <input id="name" type="text" name="name" class="input-base" value="{{ old('name') }}" required autocomplete="name" />
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="input-base" value="{{ old('email') }}" required autocomplete="email" />
                </div>

                <div>
                    <label for="password" class="form-label">Contrasena</label>
                    <input id="password" type="password" name="password" class="input-base" required autocomplete="new-password" />
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirmar password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="input-base" required autocomplete="new-password" />
                </div>

                <div class="flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="btn-primary">Registrarme</button>
                    <a href="{{ route('login') }}" class="btn-ghost">Ya tengo cuenta</a>
                </div>
            </form>
        </div>
    </div>
@endsection
