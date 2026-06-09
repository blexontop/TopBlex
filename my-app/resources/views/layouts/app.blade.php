<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TopBlex - Ropa</title>
    @vite(['resources/css/app.css','resources/css/site.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="app-body">
    @include('partials.header')

    @yield('hero')

    <main class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div style="margin-bottom:1rem;padding:0.75rem 1rem;border-radius:0.5rem;background:#dcfce7;border:1px solid #86efac;color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
