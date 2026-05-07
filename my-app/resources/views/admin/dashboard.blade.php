@extends('admin.layout')

@section('content')
    <div class="mb-12">
        <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Panel administrativo</p>
        <h1 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">Resumen de ventas</h1>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-12">
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Productos</p>
            <p style="font-size: 2rem; font-weight: 700; margin-top: 1rem;">{{ number_format($totalProducts) }}</p>
        </div>
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Stock total</p>
            <p style="font-size: 2rem; font-weight: 700; margin-top: 1rem;">{{ number_format($totalStock) }}</p>
        </div>
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Pedidos</p>
            <p style="font-size: 2rem; font-weight: 700; margin-top: 1rem;">{{ number_format($totalOrders) }}</p>
        </div>
        <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Ventas totales</p>
            <p style="font-size: 2rem; font-weight: 700; margin-top: 1rem;">EUR {{ number_format($totalRevenue, 2) }}</p>
            <p class="text-xs text-syna-muted mt-2">Mes: EUR {{ number_format($monthlyRevenue, 2) }}</p>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <section style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Productos con stock bajo</h2>
            <p class="text-xs text-syna-muted mb-4">Productos con 5 unidades o menos.</p>

            @if($lowStockProducts->isEmpty())
                <p class="text-syna-muted text-sm">No hay alertas de stock por ahora.</p>
            @else
                <ul class="space-y-2">
                    @foreach($lowStockProducts as $product)
                        <li style="display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--syna-line); padding: 0.75rem; border-radius: 6px;">
                            <span>{{ $product->name }}</span>
                            <span style="background: rgba(202, 138, 4, 0.2); color: #fbbf24; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">{{ $product->stock }} uds</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Top productos vendidos</h2>
            <p class="text-xs text-syna-muted mb-4">Basado en cantidad vendida historica.</p>

            @if($topProducts->isEmpty())
                <p class="text-syna-muted text-sm">Aun no hay ventas registradas.</p>
            @else
                <ul class="space-y-2">
                    @foreach($topProducts as $item)
                        <li style="display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--syna-line); padding: 0.75rem; border-radius: 6px;">
                            <span>{{ $item->product_name ?: 'Producto sin nombre' }}</span>
                            <span style="background: rgba(255, 255, 255, 0.08); color: var(--syna-text); padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">{{ (int) $item->total_sold }} uds</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
