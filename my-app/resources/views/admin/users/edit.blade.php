@extends('admin.layout')

@section('content')
    <h1 class="section-title">Editar usuario</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required />
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required />
        </div>

        <div>
            <label>Telefono</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
        </div>

        <div>
            <label>Ciudad</label>
            <input type="text" name="city" value="{{ old('city', $user->city) }}" />
        </div>

        <div>
            <label>Direccion</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" />
        </div>

        <div>
            <label>Contraseña (dejar vacío para no cambiar)</label>
            <input type="password" name="password" />
        </div>

        <div>
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" />
        </div>

        <div>
            <label>Es administrador</label>
            <input type="checkbox" name="is_admin" value="1" {{ $user->isAdmin() ? 'checked' : '' }} />
        </div>

        <button type="submit" class="btn-primary">Guardar</button>
    </form>
@endsection
