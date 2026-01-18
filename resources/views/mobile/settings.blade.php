@extends('layouts.mobile')

@section('content')
    <div class="fade-in settings-screen">
        <style>
            .settings-screen {
                padding-bottom: 50px;
            }

            .settings-header {
                margin-bottom: 25px;
            }

            .settings-section-title {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: rgba(255, 255, 255, 0.4);
                margin: 25px 0 10px 15px;
            }

            .light-mode .settings-section-title {
                color: rgba(0, 0, 0, 0.4);
            }

            .settings-group {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 20px;
                overflow: hidden;
                margin-bottom: 10px;
            }

            .light-mode .settings-group {
                background: #ffffff;
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            }

            .settings-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.03);
                text-decoration: none;
                color: inherit;
                transition: background 0.2s;
            }

            .settings-row:last-child {
                border-bottom: none;
            }

            .settings-row:active {
                background: rgba(255, 255, 255, 0.05);
            }

            .light-mode .settings-row:active {
                background: rgba(0, 0, 0, 0.02);
            }

            .light-mode .settings-row {
                border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            }

            .settings-row-left {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .settings-icon-box {
                width: 32px;
                height: 32px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }

            .settings-label {
                font-size: 14px;
                font-weight: 500;
            }

            .settings-row-right {
                display: flex;
                align-items: center;
                gap: 8px;
                color: rgba(255, 255, 255, 0.4);
                font-size: 13px;
            }

            .light-mode .settings-row-right {
                color: rgba(0, 0, 0, 0.4);
            }

            .settings-input-ghost {
                background: transparent;
                border: none;
                color: white;
                text-align: right;
                font-size: 14px;
                font-family: inherit;
                outline: none;
                padding: 0;
            }

            .light-mode .settings-input-ghost {
                color: #1e293b;
            }

            .settings-select-ghost {
                background: transparent;
                border: none;
                color: white;
                font-size: 14px;
                outline: none;
                appearance: none;
                text-align: right;
                padding-right: 5px;
            }

            .light-mode .settings-select-ghost {
                color: #1e293b;
            }

            /* Grid for colors in list */
            .settings-color-grid {
                padding: 16px;
                display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 10px;
            }

            .color-bubble {
                width: 100%;
                aspect-ratio: 1;
                border-radius: 50%;
                border: 2px solid transparent;
                transition: 0.2s;
                cursor: pointer;
                position: relative;
            }

            .color-bubble.active {
                border-color: white;
                transform: scale(1.1);
            }

            .light-mode .color-bubble.active {
                border-color: #334155;
            }

            .color-bubble .check {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                font-size: 12px;
                opacity: 0;
            }

            .color-bubble.active .check {
                opacity: 1;
            }

            .save-btn-container {
                margin-top: 30px;
                padding: 0 15px;
            }

            .btn-premium {
                width: 100%;
                padding: 16px;
                border-radius: 16px;
                border: none;
                font-weight: 700;
                font-size: 15px;
                color: white;
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-dark));
                box-shadow: 0 10px 20px rgba(var(--accent-rgb), 0.2);
                transition: 0.2s;
            }

            .btn-premium:active {
                transform: scale(0.98);
                box-shadow: 0 5px 10px rgba(var(--accent-rgb), 0.15);
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

            .light-mode .slider {
                background-color: #e2e8f0;
            }

            input:checked+.slider {
                background-color: var(--accent-blue);
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

        <div class="settings-header">
            <h2 class="text-bold">Paramètres</h2>
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf

            <!-- SECTION: COMPTE -->
            <p class="settings-section-title">Compte</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box"
                            style="background: rgba(var(--accent-rgb), 0.1); color: var(--accent-blue);">👤</div>
                        <span class="settings-label">Nom complet</span>
                    </div>
                    <div class="settings-row-right">
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="settings-input-ghost"
                            required>
                    </div>
                </div>
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box"
                            style="background: rgba(var(--accent-rgb), 0.1); color: var(--accent-blue);">✉️</div>
                        <span class="settings-label">Email</span>
                    </div>
                    <div class="settings-row-right">
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="settings-input-ghost"
                            required>
                    </div>
                </div>
                <a href="{{ route('analytics') }}" class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box"
                            style="background: rgba(var(--accent-rgb), 0.1); color: var(--accent-blue);">📊</div>
                        <span class="settings-label">Vision Globale</span>
                    </div>
                    <div class="settings-row-right">
                        <span>Voir analyses</span>
                        <span>→</span>
                    </div>
                </a>
            </div>

            <!-- SECTION: APPARENCE -->
            <p class="settings-section-title">Apparence</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box" style="background: #fbbf2420; color: #fbbf24;">☀️</div>
                        <span class="settings-label">Mode Clair</span>
                    </div>
                    <div class="settings-row-right">
                        <label class="switch">
                            <input type="checkbox" name="theme_mode" value="1" {{ $settings->theme == 'light' ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div style="border-bottom: 1px solid rgba(255, 255, 255, 0.03);">
                    <div class="settings-row" style="border-bottom: none;">
                        <div class="settings-row-left">
                            <div class="settings-icon-box"
                                style="background: rgba(var(--accent-rgb), 0.1); color: var(--accent-blue);">🎨</div>
                            <span class="settings-label">Couleur d'accentuation</span>
                        </div>
                    </div>
                    <div class="settings-color-grid">
                        @php
                            $colors = [
                                'blue' => '#3B82F6',
                                'emerald' => '#10B981',
                                'rose' => '#F43F5E',
                                'amber' => '#F59E0B',
                                'indigo' => '#6366F1',
                                'purple' => '#A855F7',
                                'zinc' => '#71717a',
                                'orange' => '#f97316',
                                'cyan' => '#06b6d4',
                                'lime' => '#84cc16',
                                'pink' => '#ec4899',
                                'teal' => '#14b8a6',
                            ];
                        @endphp
                        @foreach($colors as $key => $hex)
                            <label class="color-bubble {{ ($settings->accent_color ?? 'blue') == $key ? 'active' : '' }}"
                                style="background: {{ $hex }};" onclick="selectColor(this, '{{ $key }}')">
                                <input type="radio" name="accent_color" value="{{ $key }}" style="display: none;" {{ ($settings->accent_color ?? 'blue') == $key ? 'checked' : '' }}>
                                <span class="check">✓</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION: PRÉFÉRENCES -->
            <p class="settings-section-title">Préférences</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box" style="background: #10b98120; color: #10b981;">💰</div>
                        <span class="settings-label">Devise</span>
                    </div>
                    <div class="settings-row-right">
                        <select name="currency" class="settings-select-ghost">
                            <option value="FCFA" {{ $settings->currency == 'FCFA' ? 'selected' : '' }}>FCFA</option>
                            <option value="EUR" {{ $settings->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="USD" {{ $settings->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="GBP" {{ $settings->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="JPY" {{ $settings->currency == 'JPY' ? 'selected' : '' }}>JPY</option>
                            <option value="CNY" {{ $settings->currency == 'CNY' ? 'selected' : '' }}>CNY</option>
                            <option value="CAD" {{ $settings->currency == 'CAD' ? 'selected' : '' }}>CAD</option>
                        </select>
                        <span>⌵</span>
                    </div>
                </div>
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box" style="background: #3b82f620; color: #3b82f6;">🌐</div>
                        <span class="settings-label">Langue</span>
                    </div>
                    <div class="settings-row-right">
                        <select name="language" class="settings-select-ghost">
                            <option value="fr" {{ $settings->language == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ $settings->language == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ $settings->language == 'es' ? 'selected' : '' }}>Español</option>
                            <option value="pt" {{ $settings->language == 'pt' ? 'selected' : '' }}>Português</option>
                        </select>
                        <span>⌵</span>
                    </div>
                </div>
            </div>

            <!-- SECTION: SYSTÈME -->
            <p class="settings-section-title">Système</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-row-left">
                        <div class="settings-icon-box" style="background: #f43f5e20; color: #f43f5e;">🔔</div>
                        <span class="settings-label">Notifications</span>
                    </div>
                    <div class="settings-row-right">
                        <label class="switch">
                            <input type="checkbox" name="notifications_enabled" value="1" {{ $settings->notifications_enabled ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="settings-row">
                        <div class="settings-row-left">
                            <div class="settings-icon-box" style="background: #6366f120; color: #6366f1;">🛡️</div>
                            <span class="settings-label">Administration</span>
                        </div>
                        <div class="settings-row-right">
                            <span>→</span>
                        </div>
                    </a>
                @endif
            </div>

            <div class="save-btn-container">
                <button type="submit" class="btn-premium">Enregistrer les réglages</button>
            </div>
        </form>

        <div style="margin-top: 25px; text-align: center; opacity: 0.5;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    style="background: none; border: none; color: white; font-size: 13px; text-decoration: underline; cursor: pointer;"
                    class="logout-btn">Déconnexion</button>
            </form>
            <p style="font-size: 10px; margin-top: 15px;">Version 1.2.0 - Manage Premium</p>
        </div>

        <style>
            .light-mode .logout-btn {
                color: #1e293b !important;
            }
        </style>
    </div>

    <script>
        function selectColor(el, key) {
            document.querySelectorAll('.color-bubble').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            el.querySelector('input').checked = true;
        }
    </script>
@endsection