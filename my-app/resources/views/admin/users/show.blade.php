@extends('admin.layout')

@section('content')
    <h1 class="section-title">Usuario: {{ $user->name }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Telefono:</strong> {{ $user->phone }}</p>
            <p><strong>Ciudad:</strong> {{ $user->city }}</p>
            <p><strong>Direccion:</strong> {{ $user->address }}</p>
            <p><strong>Admin:</strong> {{ $user->isAdmin() ? 'Sí' : 'No' }}</p>
        </div>
    </div>

    <h2 class="section-subtitle">Pedidos</h2>
    @if($orders->isEmpty())
        <div>No hay pedidos.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->code }}</td>
                        <td>{{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->items->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">Editar usuario</a>
@endsection
