@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Mi cuenta</h1>
        <div class="section-muted">Tu informacion personal</div>
    </div>

    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="panel-card mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('orders.index') }}" class="btn-ghost">Ver mis orders</a>
            <a href="{{ route('contact.index') }}" class="btn-ghost">Contactar soporte</a>
            <a href="{{ route('faqs.index') }}" class="btn-ghost">Preguntas frecuentes</a>
            <a href="{{ route('cart.index') }}" class="btn-ghost">Ir al carrito</a>
        </div>
    </div>

    <div class="panel-card mb-8">
        <h2 class="account-block-title">Tu informacion</h2>
        <form action="{{ route('account.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="name">Nombre</label>
                    <input id="name" type="text" name="name" class="input-base" value="{{ old('name', $user->name) }}" required />
                </div>

                <div>
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="input-base" value="{{ old('email', $user->email) }}" required />
                </div>

                <div>
                    <label class="form-label" for="phone">Telefono</label>
                    <input id="phone" type="text" name="phone" class="input-base" value="{{ old('phone', $user->phone) }}" />
                </div>

                <div>
                    <label class="form-label" for="city">Ciudad</label>
                    <input id="city" type="text" name="city" class="input-base" value="{{ old('city', $user->city) }}" />
                </div>
            </div>

            <div>
                <label class="form-label" for="address">Address</label>
                <input id="address" type="text" name="address" class="input-base" value="{{ old('address', $user->address) }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="password">Nueva password (opcional)</label>
                    <input id="password" type="password" name="password" class="input-base" autocomplete="new-password" />
                </div>

                <div>
                    <label class="form-label" for="password_confirmation">Confirmar nueva password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="input-base" autocomplete="new-password" />
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary">Guardar informacion</button>
            </div>
        </form>
    </div>
@endsection
