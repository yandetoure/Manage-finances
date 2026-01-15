<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ config('app.name', 'Manage') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/manage.css') }}">

    <style>
        body {
            padding-bottom: 0;
            display: flex;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #1e293b;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            flex-shrink: 0;
        }

        .main-content {
            flex-grow: 1;
            height: 100vh;
            overflow-y: auto;
            padding: 40px;
        }

        .sidebar-nav {
            margin-top: 40px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-blue);
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <h2 class="text-bold text-blue">Manage Admin</h2>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                <span>Utilisateurs</span>
            </a>
            <a href="{{ route('admin.modules.index') }}" class="sidebar-link">
                <span>Modules & SaaS</span>
            </a>
            <hr style="opacity: 0.1; margin: 20px 0;">
            <a href="{{ route('home') }}" class="sidebar-link">
                <span>Retour App</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>