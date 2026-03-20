@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Contacto</h1>
        <div class="section-muted">Te respondemos en menos de 24h</div>
    </div>

    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="panel-card">
        <form method="POST" action="{{ route('contacto.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text" class="input-base" value="{{ old('nombre', auth()->user()->name ?? '') }}" required />
                </div>

                <div>
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="input-base" value="{{ old('email', auth()->user()->email ?? '') }}" required />
                </div>
            </div>

            <div>
                <label class="form-label" for="asunto">Asunto</label>
                <input id="asunto" name="asunto" type="text" class="input-base" value="{{ old('asunto') }}" required />
            </div>

            <div>
                <label class="form-label" for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="6" class="textarea-base" required>{{ old('mensaje') }}</textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary">Enviar mensaje</button>
            </div>
        </form>
    </div>
@endsection
