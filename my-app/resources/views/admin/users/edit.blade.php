@extends('admin.layout')

@section('content')
    <div class="mb-12">
        <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Editar usuario</p>
        <h1 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">{{ $user->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1rem;">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Telefono</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Direccion</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Contraseña (dejar vacío para no cambiar)</label>
                <input type="password" name="password" style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" style="width:100%; background: var(--syna-bg); border:1px solid var(--syna-line); border-radius:6px; padding:0.75rem;" />
            </div>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_admin" value="1" {{ $user->isAdmin() ? 'checked' : '' }} class="mr-2" />
                <span>Es administrador</span>
            </label>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="topbar-btn-solid">Guardar</button>
            <a href="{{ route('admin.users.show', $user) }}" class="topbar-btn-ghost">Cancelar</a>
        </div>
    </form>
@endsection
