@extends('admin.layout')

@section('content')
    <div class="mb-8">
        <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Usuario</p>
        <h1 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">{{ $user->name }}</h1>
    </div>

    <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Telefono:</strong> {{ $user->phone }}</p>
        <p><strong>Ciudad:</strong> {{ $user->city }}</p>
        <p><strong>Direccion:</strong> {{ $user->address }}</p>
        <p><strong>Admin:</strong> {{ $user->isAdmin() ? 'Sí' : 'No' }}</p>
    </div>

    <h2 class="text-lg font-semibold mb-4">Pedidos</h2>
    @if($orders->isEmpty())
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1rem;">No hay pedidos.</div>
    @else
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; overflow: hidden;">
            <table style="width:100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead style="background: var(--syna-bg); border-bottom: 1px solid var(--syna-line);">
                    <tr>
                        <th style="text-align:left; padding:1rem; font-weight:600; color:var(--syna-muted);">ID</th>
                        <th style="text-align:left; padding:1rem; font-weight:600; color:var(--syna-muted);">Codigo</th>
                        <th style="text-align:left; padding:1rem; font-weight:600; color:var(--syna-muted);">Total</th>
                        <th style="text-align:left; padding:1rem; font-weight:600; color:var(--syna-muted);">Estado</th>
                        <th style="text-align:left; padding:1rem; font-weight:600; color:var(--syna-muted);">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid var(--syna-line);">
                            <td style="padding:1rem;">{{ $order->id }}</td>
                            <td style="padding:1rem;">{{ $order->code }}</td>
                            <td style="padding:1rem;">EUR {{ number_format((float) $order->total, 2) }}</td>
                            <td style="padding:1rem;">{{ $order->status }}</td>
                            <td style="padding:1rem;">{{ $order->items->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.users.edit', $user) }}" class="topbar-btn-solid">Editar usuario</a>
    </div>
@endsection
