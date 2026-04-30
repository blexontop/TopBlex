<header class="sticky top-0 z-50">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="container flex items-center justify-end" style="padding-block: 0.5rem;">
            @auth
                <span class="user-greeting-mini">{{ auth()->user()->name }}</span>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="topbar-btn-ghost">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="topbar-btn-solid">Registrarse</a>
                </div>
            @endauth
        </div>
    </div>

    {{-- LOGO + BÚSQUEDA + ACCIONES --}}
    <div class="container header-main py-5">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="logo-hero">TopBlex</a>

        {{-- Búsqueda --}}
        <div class="header-search-wrap">
            <form action="{{ route('products.index') }}" method="GET" class="search-form">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar producto…" class="search-input" />
                <button type="submit" class="search-button" aria-label="Buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Acciones --}}
        <div class="header-actions">
            @auth
                <div class="user-greeting">Hola&nbsp;<strong>{{ auth()->user()->name }}</strong></div>
                <a href="{{ route('account.index') }}" class="account-link">Mi cuenta</a>
                <form action="{{ route('logout') }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="orders-link">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="account-link">Entrar</a>
                <a href="{{ route('register') }}" class="orders-link">Registro</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="cart-mini" aria-label="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:0.3rem;">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                Cart
            </a>
        </div>
    </div>

    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="header-nav">
        <div class="container flex items-center justify-between">
            <div class="nav-links">
                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'nav-item--active' : '' }}">
                    <span class="nav-item-icon">&#9632;</span> Tienda
                </a>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'nav-item--active' : '' }}">
                    <span class="nav-item-icon">&#9632;</span> Categorías
                </a>
                <a href="{{ route('collections.index') }}" class="nav-item {{ request()->routeIs('collections.*') ? 'nav-item--active' : '' }}">
                    <span class="nav-item-icon">&#9632;</span> Colecciones
                </a>
            </div>
            @auth
            <div class="nav-links">
                <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'nav-item--active' : '' }}">Mis orders</a>
                <a href="{{ route('contact.index') }}" class="nav-item">Contacto</a>
            </div>
            @endauth
        </div>
    </nav>

</header>
