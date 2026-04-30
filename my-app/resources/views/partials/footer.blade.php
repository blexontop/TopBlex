<footer class="site-footer">
    <div class="container py-10 text-sm">
        <div class="flex flex-col md:flex-row md:justify-between">
            <div class="mb-4 md:mb-0">
                <strong class="footer-title">TopBlex</strong>
                <div class="mt-2 section-muted">Ropa de calidad. Envios y devoluciones faciles.</div>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <div class="footer-title">Tienda</div>
                    <ul>
                        <li><a class="footer-link" href="{{ route('products.index') }}">Productos</a></li>
                        <li><a class="footer-link" href="{{ route('products.index') }}">Categorias (filtro en tienda)</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">Soporte</div>
                    <ul>
                        <li><a class="footer-link" href="{{ route('contact.index') }}">Contacto</a></li>
                        <li><a class="footer-link" href="{{ route('faqs.index') }}">Preguntas frecuentes</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-6 section-muted">&copy; {{ date('Y') }} TopBlex. Todos los derechos reservados.</div>
    </div>
</footer>
