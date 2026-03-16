<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-2xl font-extrabold tracking-tight">TopBlex</a>

        <nav class="hidden md:flex items-center space-x-6">
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900">Inicio</a>
            <a href="{{ url('/productos') }}" class="text-gray-700 hover:text-gray-900">Productos</a>
            <a href="{{ url('/categorias') }}" class="text-gray-700 hover:text-gray-900">Categorías</a>
            <a href="{{ url('/colecciones') }}" class="text-gray-700 hover:text-gray-900">Colecciones</a>
        </nav>

        <div class="flex items-center space-x-4">
            <form action="{{ url('/productos') }}" method="GET" class="hidden md:block">
                <input type="text" name="q" placeholder="Buscar productos..." class="border rounded px-3 py-1" />
            </form>

            <a href="{{ url('/carrito') }}" class="text-gray-700 hover:text-gray-900">🛒</a>
        </div>
    </div>
</header>
