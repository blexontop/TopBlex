@extends('layouts.app')

@push('head')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap');

:root{
    --syna-bg: #050505;
    --syna-bg-soft: #0d0d0d;
    --syna-card: #101010;
    --syna-line: rgba(255,255,255,0.12);
    --syna-line-strong: rgba(255,255,255,0.22);
    --syna-text: #f5f5f5;
    --syna-muted: #a8a8a8;
    --syna-white: #ffffff;
    --syna-black: #000000;
}

html{
    scroll-behavior: smooth;
}

body{
    background: var(--syna-bg);
    color: var(--syna-text);
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

a{
    color: inherit;
    text-decoration: none;
}

img{
    display: block;
    width: 100%;
    max-width: 100%;
}

.container{
    max-width: 1380px;
}

/* NAV GENERAL */
header,
.site-header,
.navbar,
nav{
    background: rgba(5,5,5,0.92);
    border-bottom: 1px solid var(--syna-line);
    backdrop-filter: blur(8px);
}

header a,
.site-header a,
.navbar a,
nav a{
    text-transform: uppercase;
    letter-spacing: 0.14em;
    font-size: 0.74rem;
    font-weight: 700;
}

/* HERO */
.syna-hero{
    position: relative;
    min-height: 92vh;
    display: flex;
    align-items: flex-end;
    background-size: cover;
    background-position: center center;
    border-bottom: 1px solid var(--syna-line);
    overflow: hidden;
    isolation: isolate;
}

.syna-hero::before{
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to top, rgba(0,0,0,0.88) 10%, rgba(0,0,0,0.18) 45%, rgba(0,0,0,0.30) 100%);
    z-index: 0;
}

.syna-hero::after{
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05), transparent 30%),
        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.04), transparent 30%);
    mix-blend-mode: soft-light;
    z-index: 0;
}

.syna-hero .hero-overlay{
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.65), rgba(0,0,0,0.15), rgba(0,0,0,0.55));
    z-index: 0;
}

.syna-hero .hero-content{
    position: relative;
    z-index: 2;
    max-width: 560px;
    padding-bottom: 10px;
}

.syna-hero .hero-title{
    margin: 0;
    font-family: 'Oswald', sans-serif;
    font-size: clamp(4.2rem, 10vw, 8.5rem);
    line-height: 0.88;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--syna-white);
    text-shadow: 0 10px 30px rgba(0,0,0,0.45);
}

.syna-hero .hero-content p{
    margin-top: 1.4rem;
    max-width: 470px;
    color: #dddddd !important;
    font-size: 0.84rem;
    line-height: 1.9;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-weight: 500;
}

.btn-primary,
.btn-ghost{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 54px;
    padding: 0 1.7rem;
    border: 1px solid var(--syna-white);
    border-radius: 0;
    font-size: 0.74rem;
    font-weight: 800;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    transition: all 0.25s ease;
    box-shadow: none;
}

.btn-primary{
    background: var(--syna-white);
    color: var(--syna-black);
}

.btn-primary:hover{
    background: transparent;
    color: var(--syna-white);
    transform: translateY(-2px);
}

.btn-ghost{
    background: transparent;
    color: var(--syna-white);
}

.btn-ghost:hover{
    background: var(--syna-white);
    color: var(--syna-black);
    transform: translateY(-2px);
}

/* SECCION DESTACADOS */
.featured-syna{
    padding: 5rem 0 5.5rem;
}

.featured-syna h2{
    display: inline-block;
    margin-bottom: 1.6rem;
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.3rem, 2vw, 2rem);
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--syna-white);
}

.featured-syna h2::after{
    content: "";
    display: block;
    width: 100%;
    height: 1px;
    margin-top: 0.65rem;
    background: var(--syna-white);
    opacity: 0.28;
}

/* GRID PRODUCTOS */
.product-grid{
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1px;
    background: var(--syna-line);
    border: 1px solid var(--syna-line);
}

.product-grid > *{
    position: relative;
    background: var(--syna-bg);
    min-width: 0;
    transition: transform 0.28s ease, background 0.28s ease;
}

.product-grid > *:hover{
    background: var(--syna-bg-soft);
    transform: translateY(-6px);
    z-index: 2;
}

.product-grid > * > a,
.product-grid > * a:first-child{
    display: block;
    height: 100%;
    color: inherit;
}

.product-grid > * img{
    width: 100%;
    aspect-ratio: 3 / 4;
    object-fit: cover;
    background: #161616;
}

.product-grid > * .p-4,
.product-grid > * .px-4,
.product-grid > * .py-4,
.product-grid > * .card-body,
.product-grid > * .product-card-body{
    padding: 1rem !important;
}

.product-grid > * h3,
.product-grid > * h4,
.product-grid > * .font-semibold,
.product-grid > * .product-name{
    margin: 0 0 0.5rem 0;
    color: var(--syna-white);
    font-size: 0.8rem;
    line-height: 1.45;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.product-grid > * p,
.product-grid > * span,
.product-grid > * .text-sm,
.product-grid > * .text-gray-500,
.product-grid > * .text-gray-600{
    color: var(--syna-muted);
    font-size: 0.72rem;
    letter-spacing: 0.08em;
}

.product-grid > * .price,
.product-grid > * .font-bold,
.product-grid > * .precio{
    display: inline-block;
    margin-top: 0.6rem;
    color: var(--syna-white);
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.product-grid > * .badge,
.product-grid > * .tag,
.product-grid > * .stock-badge{
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 0.42rem 0.62rem;
    background: var(--syna-white);
    color: var(--syna-black);
    font-size: 0.64rem;
    font-weight: 800;
    letter-spacing: 0.16em;
    text-transform: uppercase;
}

.product-grid > *::after{
    content: "";
    position: absolute;
    inset: 0;
    border: 1px solid transparent;
    pointer-events: none;
    transition: border-color 0.25s ease;
}

.product-grid > *:hover::after{
    border-color: var(--syna-line-strong);
}

/* FOOTER */
footer{
    border-top: 1px solid var(--syna-line);
    background: #070707;
    color: var(--syna-muted);
}

footer a{
    color: var(--syna-white);
}

/* INPUTS Y BOTONES GENERALES */
button,
input,
select,
textarea{
    font-family: 'Inter', sans-serif;
}

input,
select,
textarea{
    background: #0d0d0d;
    color: var(--syna-white);
    border: 1px solid var(--syna-line);
    border-radius: 0;
}

input:focus,
select:focus,
textarea:focus{
    outline: none;
    border-color: rgba(255,255,255,0.35);
    box-shadow: none;
}

/* RESPONSIVE */
@media (max-width: 1200px){
    .product-grid{
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 900px){
    .syna-hero{
        min-height: 78vh;
    }

    .syna-hero .hero-content{
        max-width: 100%;
    }

    .syna-hero .hero-content p{
        max-width: 100%;
    }

    .product-grid{
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px){
    .syna-hero{
        min-height: 72vh;
    }

    .syna-hero .hero-title{
        font-size: clamp(3.3rem, 16vw, 5rem);
    }

    .syna-hero .hero-content p{
        font-size: 0.75rem;
        line-height: 1.75;
        letter-spacing: 0.1em;
    }

    .btn-primary,
    .btn-ghost{
        width: 100%;
        margin: 0 0 0.75rem 0 !important;
    }

    .product-grid{
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .featured-syna{
        padding: 3.5rem 0 4rem;
    }
}
</style>
@endpush

@section('hero')
    <section class="hero-full syna-hero" style="background-image:url('https://images.unsplash.com/photo-1520975911559-0182c8e2f5c6?q=80&w=1600&auto=format&fit=crop');">
        <div class="hero-overlay"></div>

        <div class="container mx-auto px-4 py-28 relative">
            <div class="md:w-2/5 hero-content">
                <h1 class="hero-title">TopBlex</h1>

                <p>
                    Colecciones seleccionadas, materiales de calidad y envío rápido.
                    Descubre lo último en nuestra tienda.
                </p>

                <div class="mt-6">
                    <a href="{{ url('/productos') }}" class="btn-primary">Ver productos</a>
                    <a href="{{ url('/colecciones') }}" class="btn-ghost ml-3">Colecciones</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="featured-syna">
        <div class="container mx-auto px-4">
            <h2>Productos destacados</h2>

            <div class="product-grid">
                @foreach($productos as $producto)
                    @include('components.product-card', ['producto' => $producto])
                @endforeach
            </div>
        </div>
    </section>
@endsection