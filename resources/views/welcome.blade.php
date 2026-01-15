<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage - Simplifiez vos finances</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/manage.css') }}">

    <style>
        body {
            background: radial-gradient(circle at top right, #0a192f, #020617);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            padding: 20px;
        }

        .welcome-container {
            max-width: 1000px;
            width: 100%;
            text-align: center;
        }

        .hero-section {
            margin-bottom: 50px;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(to right, var(--accent-blue), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .auth-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            transition: transform 0.3s ease, border-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .auth-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-blue);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--accent-blue);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 30px;
        }

        .btn-auth {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-blue {
            background: var(--accent-blue);
            color: #fff;
        }

        .btn-secondary-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: #fff;
        }

        .quick-action {
            margin-top: 50px;
            padding: 20px;
            background: rgba(16, 185, 129, 0.05);
            border: 1px dashed rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            gap: 15px;
        }

        .quick-action-link {
            color: var(--primary-green);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.3s;
        }

        .quick-action-link:hover {
            border-color: var(--primary-green);
        }

        @media (max-width: 640px) {
            .hero-title { font-size: 2.2rem; }
            .auth-cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="welcome-container fade-in">
        <header class="hero-section">
            <h1 class="hero-title">Manage.</h1>
            <p class="hero-subtitle">Reprenez le contrôle de votre avenir financier avec une interface moderne, intuitive et automatisée.</p>
        </header>

        <div class="auth-cards">
            <!-- Login Card -->
            <div class="auth-card">
                <div class="card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </div>
                <h2 class="card-title">Connexion</h2>
                <p class="card-desc">Accédez à votre tableau de bord et gérez vos flux de trésorerie.</p>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-auth btn-primary-blue">Se connecter</a>
                @endif
            </div>

            <!-- Register Card -->
            <div class="auth-card">
                <div class="card-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary-green);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                </div>
                <h2 class="card-title">Inscription</h2>
                <p class="card-desc">Créez un compte gratuitement et commencez à épargner intelligemment.</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-auth btn-secondary-outline">Créer un compte</a>
                @endif
            </div>
        </div>

        <div class="quick-action">
            <span style="color: var(--text-muted); font-size: 0.9rem;">Mode Développeur :</span>
            <a href="/login-test" class="quick-action-link">Se connecter avec le compte de test →</a>
        </div>

        <footer style="margin-top: 60px; color: rgba(255,255,255,0.2); font-size: 0.8rem;">
            &copy; {{ date('Y') }} Manage Financial SaaS. Tous droits réservés.
        </footer>
    </div>
</body>
</html>
