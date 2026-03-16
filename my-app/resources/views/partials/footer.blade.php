<footer class="bg-gray-50 border-t mt-12">
    <div class="container mx-auto px-4 py-8 text-sm text-gray-600">
        <div class="flex flex-col md:flex-row md:justify-between">
            <div class="mb-4 md:mb-0">
                <strong>TopBlex</strong>
                <div class="mt-2">Ropa de calidad. Envíos y devoluciones fáciles.</div>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <div class="font-semibold">Tienda</div>
                    <ul>
                        <li><a href="{{ url('/productos') }}">Productos</a></li>
                        <li><a href="{{ url('/categorias') }}">Categorías</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold">Soporte</div>
                    <ul>
                        <li><a href="#">Contacto</a></li>
                        <li><a href="#">Preguntas frecuentes</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-6 text-xs text-gray-500">© {{ date('Y') }} TopBlex. Todos los derechos reservados.</div>
    </div>
</footer>
