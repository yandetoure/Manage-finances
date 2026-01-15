<!DOCTYPE html>
<html lang="{{ auth()->user()->settings->language ?? 'fr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Manage') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/manage.css') }}">

    <!-- Scripts -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body x-data="{ activeTab: 'home' }"
    class="{{ (auth()->user()->settings->theme ?? 'dark') == 'light' ? 'light-mode' : '' }}">
    <header style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="text-2xl text-bold">Manage</h1>
            <p class="text-muted" style="font-size: 12px;">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div
            style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent-blue); display: flex; align-items: center; justify-content: center;">
            <span style="font-weight: bold; color: white;">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
        </div>
    </header>

    <main style="padding: 0 20px;">
        @if (session('success'))
            <div class="fade-in"
                style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; border: 1px solid rgba(16, 185, 129, 0.3);">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="fade-in"
                style="background: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; border: 1px solid rgba(239, 68, 68, 0.3);">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="nav-icon">🏠</div>
            <span>Accueil</span>
        </a>
        <a href="{{ route('transactions') }}" class="nav-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
            <div class="nav-icon">📊</div>
            <span>Transactions</span>
        </a>
        <a href="{{ route('savings.index') }}" class="nav-item {{ request()->routeIs('savings.*') ? 'active' : '' }}">
            <div class="nav-icon">💰</div>
            <span>Épargne</span>
        </a>
        <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
            <div class="nav-icon">⚙️</div>
            <span>Paramètres</span>
        </a>
    </nav>
</body>

</html>