@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 25px;">Paramètres</h2>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <!-- Profil Section -->
            <div class="glass-card" style="margin-bottom: 20px; padding: 20px;">
                <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Profil</h3>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Nom
                            complet</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="input-modern"
                            style="width: 100%;" required>
                    </div>

                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Adresse
                            Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="input-modern"
                            style="width: 100%;" required>
                    </div>
                </div>
            </div>

            <!-- Préférences Section -->
            <div class="glass-card" style="margin-bottom: 20px; padding: 20px;">
                <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Préférences</h3>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Devise -->
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Devise
                            par défaut</label>
                        <select name="currency" class="input-modern" style="width: 100%;">
                            <option value="FCFA" {{ $settings->currency == 'FCFA' ? 'selected' : '' }}>FCFA (Franc CFA)
                            </option>
                            <option value="EUR" {{ $settings->currency == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                            <option value="USD" {{ $settings->currency == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                        </select>
                    </div>

                    <!-- Langue -->
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Langue</label>
                        <select name="language" class="input-modern" style="width: 100%;">
                            <option value="fr" {{ $settings->language == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ $settings->language == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>

                    <!-- Thème -->
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Apparence</label>
                        <div style="display: flex; gap: 10px;">
                            <label style="flex: 1; position: relative;">
                                <input type="radio" name="theme" value="dark" style="display: none;" {{ $settings->theme == 'dark' ? 'checked' : '' }}>
                                <div class="theme-option" :class="theme == 'dark' ? 'theme-selected' : ''"
                                    style="text-align: center; padding: 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.2s;">
                                    <span style="font-size: 20px; display: block;">🌙</span>
                                    <span style="font-size: 12px; font-weight: 600;">Sombre</span>
                                </div>
                            </label>
                            <label style="flex: 1; position: relative;">
                                <input type="radio" name="theme" value="light" style="display: none;" {{ $settings->theme == 'light' ? 'checked' : '' }}>
                                <div class="theme-option"
                                    style="text-align: center; padding: 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.2s;">
                                    <span style="font-size: 20px; display: block;">☀️</span>
                                    <span style="font-size: 12px; font-weight: 600;">Clair</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Notifications Switch -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                        <div>
                            <span class="text-bold" style="font-size: 14px; display: block;">Notifications Push</span>
                            <span class="text-muted" style="font-size: 11px;">Alertes et rappels</span>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="notifications_enabled" value="1" {{ $settings->notifications_enabled ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent"
                    style="width: 100%; border-radius: 12px; margin-top: 25px; padding: 14px;">Enregistrer les
                    modifications</button>
            </div>
        </form>

        <!-- Modules Section (Quick Access) -->
        <div class="glass-card" style="margin-bottom: 20px; padding: 20px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Services</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                <a href="{{ route('revenues.index') }}" class="module-mini-btn">📈</a>
                <a href="{{ route('expenses.index') }}" class="module-mini-btn">📉</a>
                <a href="{{ route('debts.index') }}" class="module-mini-btn">💸</a>
                <a href="{{ route('claims.index') }}" class="module-mini-btn">🤝</a>
                <a href="{{ route('savings.index') }}" class="module-mini-btn">💰</a>
                <a href="{{ route('forecasts.index') }}" class="module-mini-btn">🔮</a>
            </div>
        </div>

        @if (auth()->user()->hasRole('admin'))
            <div style="margin-top: 20px;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"
                    style="width: 100%; background: #1e293b; border: 1px solid var(--accent-blue); border-radius: 12px;">Accéder
                    à l'Admin</a>
            </div>
        @endif

        <div style="margin-top: 30px; text-align: center; padding-bottom: 40px;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-muted"
                    style="background: none; border: none; font-size: 14px; text-decoration: underline; cursor: pointer;">Déconnexion</button>
            </form>
            <p class="text-muted" style="font-size: 10px; margin-top: 20px;">Version 1.1.0 - Manage Financial SaaS</p>
        </div>
    </div>

    <style>
        .module-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 10px;
            border-radius: 16px;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .module-btn:active {
            transform: scale(0.95);
        }

        .module-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .module-label {
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }

        .input-modern {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 12px;
            color: white;
            outline: none;
            transition: 0.2s;
        }

        .input-modern:focus {
            border-color: var(--accent-blue);
        }

        .module-mini-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            text-decoration: none;
            transition: 0.2s;
        }

        .module-mini-btn:active {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(0.95);
        }

        .theme-option {
            background: rgba(255, 255, 255, 0.03);
        }

        input[type="radio"]:checked+.theme-option {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6 !important;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }

        /* Switch Toggle */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #22c55e;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        .slider.round {
            border-radius: 24px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection