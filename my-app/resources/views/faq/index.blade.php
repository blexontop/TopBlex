@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1 class="section-title">Preguntas frecuentes</h1>
        <div class="section-muted">Respuestas rapidas</div>
    </div>

    @if($preguntas->isEmpty())
        <div class="panel-card">
            <p class="section-muted">Todavia no hay preguntas cargadas.</p>
        </div>
    @else
        <div class="grid gap-3">
            @foreach($preguntas as $pregunta)
                <article class="faq-item">
                    <h3>{{ $pregunta->pregunta }}</h3>
                    <p>{{ $pregunta->respuesta }}</p>
                </article>
            @endforeach
        </div>
    @endif
@endsection
