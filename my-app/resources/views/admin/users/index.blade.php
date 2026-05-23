@extends('admin.layout')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Usuarios</p>
            <h1 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">Gestion de usuarios</h1>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-8 flex flex-col gap-3 sm:flex-row" style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1rem;">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Buscar por nombre o email"
            style="flex: 1; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;"
        >
        <button type="submit" class="topbar-btn-solid">Buscar</button>
    </form>

    <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead style="background: var(--syna-bg); border-bottom: 1px solid var(--syna-line);">
                <tr>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">ID</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Nombre</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Email</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Admin</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid var(--syna-line);">
                        <td style="padding: 1rem;">{{ $user->id }}</td>
                        <td style="padding: 1rem;">{{ $user->name }}</td>
                        <td style="padding: 1rem;">{{ $user->email }}</td>
                        <td style="padding: 1rem;">{{ $user->isAdmin() ? 'Sí' : 'No' }}</td>
                        <td style="padding: 1rem;">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem;">Ver</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem;">Editar</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Eliminar usuario?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem; border-color: rgba(239, 68, 68, 0.3); color: #f87171;">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--syna-muted);">No hay usuarios para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
@endsection
