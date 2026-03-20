<header class="sticky top-0 z-50">
    <div class="topbar">
        <div class="container flex items-center justify-between py-2">
            <div class="flex items-center space-x-4">
                <span class="uppercase">ES</span>
                <a href="{{ route('contacto') }}">Ayuda</a>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('cuenta') }}">Mi cuenta</a>
                    <a href="{{ route('pedidos') }}">Pedidos</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesion</a>
                    <a href="{{ route('register') }}">Registro</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="container header-main py-4">
        <div class="header-left flex items-center space-x-6">
            <a href="{{ route('home') }}" class="logo">TopBlex</a>

            <nav class="hidden lg:flex items-center space-x-6 main-nav">
                <a href="{{ route('productos.index') }}">Tienda</a>
                <a href="{{ route('categorias.index') }}">Categorias</a>
                <a href="{{ route('colecciones.index') }}">Colecciones</a>
            </nav>
        </div>

        <div class="header-center hidden md:flex">
            <form action="{{ route('productos.index') }}" method="GET" class="search-form">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar producto" class="search-input" />
                <button type="submit" class="search-button" aria-label="Buscar productos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </button>
            </form>
        </div>

        <div class="header-actions flex items-center space-x-3 md:space-x-4">
            @auth
                <a href="{{ route('cuenta') }}" class="account-link">Mi cuenta</a>
                <a href="{{ route('pedidos') }}" class="orders-link">Pedidos</a>
                <form action="{{ route('logout') }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="orders-link">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="account-link">Iniciar sesion</a>
                <a href="{{ route('register') }}" class="orders-link">Registro</a>
            @endauth
            <a href="{{ route('carrito.index') }}" class="cart-mini" aria-label="Ir al carrito">Carrito</a>
        </div>
    </div>
</header>
