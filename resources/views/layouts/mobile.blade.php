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
        :root {
            @php
                $accentColor = auth()->user()->settings->accent_color ?? 'blue';
                $palettes = [
                    'blue'    => ['base' => '#3B82F6', 'dark' => '#2563EB', 'rgb' => '59, 130, 246'],
                    'emerald' => ['base' => '#10B981', 'dark' => '#059669', 'rgb' => '16, 185, 129'],
                    'rose'    => ['base' => '#F43F5E', 'dark' => '#E11D48', 'rgb' => '244, 63, 94'],
                    'amber'   => ['base' => '#F59E0B', 'dark' => '#D97706', 'rgb' => '245, 158, 11'],
                    'indigo'  => ['base' => '#6366F1', 'dark' => '#4F46E5', 'rgb' => '99, 102, 241'],
                    'purple'  => ['base' => '#A855F7', 'dark' => '#9333EA', 'rgb' => '168, 85, 247'],
                    'zinc'    => ['base' => '#71717a', 'dark' => '#52525b', 'rgb' => '113, 113, 122'],
                    'orange'  => ['base' => '#f97316', 'dark' => '#ea580c', 'rgb' => '249, 115, 22'],
                    'cyan'    => ['base' => '#06b6d4', 'dark' => '#0891b2', 'rgb' => '6, 182, 212'],
                    'lime'    => ['base' => '#84cc16', 'dark' => '#65a30d', 'rgb' => '132, 204, 22'],
                    'pink'    => ['base' => '#ec4899', 'dark' => '#db2777', 'rgb' => '236, 72, 153'],
                    'teal'    => ['base' => '#14b8a6', 'dark' => '#0d9488', 'rgb' => '20, 184, 166'],
                ];
                $palette = $palettes[$accentColor] ?? $palettes['blue'];
            @endphp
            --accent-blue: {{ $palette['base'] }};
            --accent-dark: {{ $palette['dark'] }};
            --accent-rgb: {{ $palette['rgb'] }};
            --primary-green: {{ $palette['base'] }};

            /* Default Dark Mode Variables */
            --bg-color: #0c111d;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-muted: #94A3B8;
            --input-bg: rgba(255, 255, 255, 0.05);
            --nav-bg: rgba(15, 23, 42, 0.8);
        }

        .light-mode {
            --bg-color: #F3F4F6;
            --card-bg: #ffffff;
            --card-border: rgba(0, 0, 0, 0.05);
            --text-main: #111827;
            --text-muted: #6B7280;
            --input-bg: #ffffff;
            --nav-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .glass-card {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .nav-item {
            position: relative;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            color: var(--accent-blue) !important;
            background: rgba(var(--accent-rgb), 0.1);
            border-radius: 16px;
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--accent-blue);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--accent-blue);
        }

        .nav-item.active .nav-icon {
            transform: translateY(-2px);
            filter: drop-shadow(0 0 5px rgba(var(--accent-rgb), 0.5));
        }

        .bottom-nav {
            background: var(--nav-bg) !important;
            border: 1px solid var(--card-border) !important;
        }

        /* Global Overrides for Forms and Components */
        .input-modern {
            background: var(--input-bg) !important;
            border: 1px solid var(--card-border) !important;
            color: var(--text-main) !important;
        }

        .category-item {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            color: var(--text-main) !important;
        }

        .slider {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--card-border) !important;
        }

        .text-bold {
            color: var(--text-main) !important;
        }

        header h1 {
            color: var(--accent-blue) !important;
            text-shadow: 0 0 15px rgba(var(--accent-rgb), 0.2);
        }

        .text-green, .text-success {
            color: var(--accent-blue) !important;
        }

        /* Button Colors Alignment */
        .btn-primary, .btn-accent, .btn-premium {
            background: var(--accent-blue) !important;
            color: white !important;
            box-shadow: 0 10px 20px rgba(var(--accent-rgb), 0.2) !important;
        }

        .btn-primary:active, .btn-accent:active, .btn-premium:active {
            transform: scale(0.98);
            opacity: 0.9;
        }
    </style>
</head>

<body x-data="{ activeTab: 'home' }"
    class="{{ (auth()->user()->settings->theme ?? 'dark') == 'light' ? 'light-mode' : '' }}">
    <header style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="text-2xl text-bold" style="color: var(--accent-blue);">Manage</h1>
            <p class="text-muted" style="font-size: 12px;">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div
            style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent-blue); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(var(--accent-rgb), 0.3);">
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
        @if(auth()->user()->hasRole('admin') && request()->is('admin*'))
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">📊</div>
                <span>Stats</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <div class="nav-icon">👥</div>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.modules.index') }}"
                class="nav-item {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                <div class="nav-icon">🛠️</div>
                <span>Modules</span>
            </a>
            <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
                <div class="nav-icon">⚙️</div>
                <span>Admin</span>
            </a>
        @else
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <div class="nav-icon">🏠</div>
                <span>Accueil</span>
            </a>
            <a href="{{ route('transactions') }}" class="nav-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
                <div class="nav-icon">📊</div>
                <span>Transac</span>
            </a>
            <a href="{{ route('savings.index') }}" class="nav-item {{ request()->routeIs('savings.*') ? 'active' : '' }}">
                <div class="nav-icon">💰</div>
                <span>Épargne</span>
            </a>
            <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
                <div class="nav-icon">⚙️</div>
                <span>Param.</span>
            </a>
        @endif
    </nav>
</body>

</html>