<header class="sticky top-0 z-50 bg-white">
    <div class="topbar bg-gray-50 text-xs text-gray-600">
        <div class="container mx-auto px-4 py-1 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="uppercase">ES</span>
                <a href="#" class="hover:underline">Ayuda</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:underline">Mi cuenta</a>
                <a href="#" class="hover:underline">Pedidos</a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <a href="{{ url('/') }}" class="logo text-2xl font-extrabold tracking-tight">TopBlex</a>

            <nav class="hidden lg:flex items-center space-x-6 main-nav">
                <a href="{{ url('/productos') }}" class="hover:underline">Tienda</a>
                <a href="{{ url('/categorias') }}" class="hover:underline">Categorías</a>
                <a href="{{ url('/colecciones') }}" class="hover:underline">Colecciones</a>
            </nav>
        </div>

        <div class="flex items-center space-x-4">
            <form action="{{ url('/productos') }}" method="GET" class="hidden md:flex items-center border rounded-full px-3 py-1 w-72">
                <input type="text" name="q" placeholder="Buscar productos, p. ej. " class="flex-1 px-2 text-sm outline-none" />
                <button type="submit" class="text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </button>
            </form>

            <a href="{{ url('/carrito') }}" class="text-gray-700 hover:text-gray-900">🛒</a>
        </div>
    </div>
</header>
