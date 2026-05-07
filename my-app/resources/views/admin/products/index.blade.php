@extends('admin.layout')

@section('content')
    <div class="mb-12 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Catalogo</p>
            <h1 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">Gestion de productos</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="topbar-btn-solid">Crear producto</a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-8 flex flex-col gap-3 sm:flex-row" style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 1rem;">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="Buscar por nombre o SKU"
            style="flex: 1; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;"
        >
        <button type="submit" class="topbar-btn-solid">Buscar</button>
    </form>

    <div style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead style="background: var(--syna-bg); border-bottom: 1px solid var(--syna-line);">
                <tr>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Producto</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Precio</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Stock</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Estado</th>
                    <th style="text-align: left; padding: 1rem; font-weight: 600; color: var(--syna-muted);">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr style="border-bottom: 1px solid var(--syna-line);">
                        <td style="padding: 1rem;">
                            <p style="font-weight: 600;">{{ $product->name }}</p>
                            <p class="text-xs text-syna-muted">SKU: {{ $product->sku ?: 'N/A' }}</p>
                        </td>
                        <td style="padding: 1rem;">EUR {{ number_format((float) $product->price, 2) }}</td>
                        <td style="padding: 1rem;">
                            <p style="font-weight: 600; margin-bottom: 0.5rem;">{{ (int) $product->stock }} uds</p>
                            <form method="POST" action="{{ route('admin.products.stock', $product) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="adjustment" value="1" style="width: 60px; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 4px; padding: 0.5rem; color: var(--syna-text); font-size: 0.75rem;">
                                <button type="submit" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem;">Ajustar</button>
                            </form>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="display: inline-block; padding: 0.5rem 0.75rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; {{ $product->is_visible ? 'background: rgba(34, 197, 94, 0.15); color: #86efac;' : 'background: rgba(255, 255, 255, 0.08); color: var(--syna-muted);' }}">
                                {{ $product->is_visible ? 'Visible' : 'Oculto' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem;">Editar</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Eliminar producto?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="topbar-btn-ghost" style="padding: 0.3rem 0.75rem; font-size: 0.7rem; border-color: rgba(239, 68, 68, 0.3); color: #f87171;">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--syna-muted);">No hay productos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
