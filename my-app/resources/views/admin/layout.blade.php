<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TopBlex Admin</title>
    @vite(['resources/css/app.css','resources/css/site.css','resources/js/app.js'])
</head>
<body class="app-body">
    <header class="site-header">
        <div class="container header-main py-5">
            <a href="{{ route('admin.dashboard') }}" class="logo">TopBlex Admin</a>
            <nav class="flex items-center justify-end gap-6">
                <a href="{{ route('admin.dashboard') }}" class="topbar a" style="padding: 0.3rem 0; font-size: 0.75rem;">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="topbar a" style="padding: 0.3rem 0; font-size: 0.75rem;">Productos</a>
                <a href="{{ route('home') }}" class="topbar a" style="padding: 0.3rem 0; font-size: 0.75rem;">Ver tienda</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="topbar a" style="padding: 0.3rem 0; font-size: 0.75rem;">Salir</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container py-12">
        @if(session('success'))
            <div class="mb-8 p-4 rounded" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 rounded" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
