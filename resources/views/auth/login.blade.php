<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Manage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
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

        .auth-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: var(--accent-blue);
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--accent-blue);
            color: #fff;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            transition: transform 0.2s;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .error-msg {
            color: #f87171;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="auth-card fade-in">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 10px;">Bon retour !</h1>
        <p style="color: var(--text-muted); margin-bottom: 30px;">Veuillez vous connecter à votre compte.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-input" placeholder="exemple@mail.com" required
                    value="{{ old('email') }}">
                @error('email') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                @error('password') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

        <p style="margin-top: 25px; font-size: 0.9rem; color: var(--text-muted);">
            Vous n'avez pas de compte ? <a href="{{ route('register') }}"
                style="color: var(--accent-blue); text-decoration: none; font-weight: 600;">S'inscrire</a>
        </p>

        <hr style="opacity: 0.05; margin: 25px 0;">
        <a href="/" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">← Retour à
            l'accueil</a>
    </div>
</body>

</html>