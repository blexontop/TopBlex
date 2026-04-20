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
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
