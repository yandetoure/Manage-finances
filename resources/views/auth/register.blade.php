<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Manage</title>
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
            max-width: 450px;
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
            border-color: var(--primary-green);
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-green);
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
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 10px;">Bienvenue !</h1>
        <p style="color: var(--text-muted); margin-bottom: 30px;">Créez votre compte en quelques secondes.</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nom Complet</label>
                <input type="text" name="name" class="form-input" placeholder="Jean Dupont" required
                    value="{{ old('name') }}">
                @error('name') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-input" placeholder="exemple@mail.com" required
                    value="{{ old('email') }}">
                @error('email') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••"
                        required>
                </div>
            </div>
            @error('password') <p class="error-msg" style="margin-top: -10px; margin-bottom: 15px;">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <p style="margin-top: 25px; font-size: 0.9rem; color: var(--text-muted);">
            Vous avez déjà un compte ? <a href="{{ route('login') }}"
                style="color: var(--primary-green); text-decoration: none; font-weight: 600;">Se connecter</a>
        </p>

        <hr style="opacity: 0.05; margin: 25px 0;">
        <a href="/" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">← Retour à
            l'accueil</a>
    </div>
</body>

</html>