@extends('admin.layout')

@section('content')
    <div class="mb-12">
        <p class="text-xs uppercase tracking-[0.12em] text-syna-muted">Catalogo</p>
        <h1 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; letter-spacing: 0.05em; margin-top: 0.5rem;">Crear producto</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" style="background: var(--syna-card); border: 1px solid var(--syna-line); border-radius: 8px; padding: 2rem; max-width: 800px;">
        @csrf

        @include('admin.products._form')

        <div class="mt-8 flex items-center gap-4">
            <button type="submit" class="topbar-btn-solid">Guardar producto</button>
            <a href="{{ route('admin.products.index') }}" class="text-syna-muted hover:text-white" style="font-size: 0.875rem;">Cancelar</a>
        </div>
    </form>
@endsection
