<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TopBlex - Ropa</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="app-body antialiased">
    @include('partials.header')

    @yield('hero')

    <main class="container app-main">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
