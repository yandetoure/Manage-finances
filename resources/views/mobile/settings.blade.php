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
                    <a href="{{ route('analytics') }}" class="glass-card"
                        style="display: flex; align-items: center; justify-content: space-between; padding: 15px; text-decoration: none; border: 1px solid var(--accent-blue);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 20px;">📊</span>
                            <div>
                                <p class="text-bold" style="font-size: 14px; color: white;">Ma Vision Globale</p>
                                <p class="text-muted" style="font-size: 11px;">Analyses mensuelles détaillées</p>
                            </div>
                        </div>
                        <span style="color: var(--accent-blue);">→</span>
                    </a>

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
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Devise
                            par défaut</label>
                        <select name="currency" class="input-modern" style="width: 100%;">
                            <optgroup label="Courantes">
                                <option value="FCFA" {{ $settings->currency == 'FCFA' ? 'selected' : '' }}>FCFA (Franc CFA)
                                </option>
                                <option value="EUR" {{ $settings->currency == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="USD" {{ $settings->currency == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                            </optgroup>
                            <optgroup label="Internationales">
                                <option value="GBP" {{ $settings->currency == 'GBP' ? 'selected' : '' }}>GBP (Livre Sterling)
                                </option>
                                <option value="JPY" {{ $settings->currency == 'JPY' ? 'selected' : '' }}>JPY (Yen)</option>
                                <option value="CNY" {{ $settings->currency == 'CNY' ? 'selected' : '' }}>CNY (Yuan)</option>
                                <option value="CAD" {{ $settings->currency == 'CAD' ? 'selected' : '' }}>CAD (Dollar Canadien)
                                </option>
                                <option value="CHF" {{ $settings->currency == 'CHF' ? 'selected' : '' }}>CHF (Franc Suisse)
                                </option>
                                <option value="AUD" {{ $settings->currency == 'AUD' ? 'selected' : '' }}>AUD (Dollar
                                    Australien)</option>
                                <option value="ZAR" {{ $settings->currency == 'ZAR' ? 'selected' : '' }}>ZAR (Rand
                                    Sud-Africain)</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Langue -->
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Langue
                            de l'interface</label>
                        <select name="language" class="input-modern" style="width: 100%;">
                            <option value="fr" {{ $settings->language == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                            <option value="en" {{ $settings->language == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                            <option value="es" {{ $settings->language == 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
                            <option value="pt" {{ $settings->language == 'pt' ? 'selected' : '' }}>🇵🇹 Português</option>
                            <option value="ar" {{ $settings->language == 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                            <option value="de" {{ $settings->language == 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                            <option value="it" {{ $settings->language == 'it' ? 'selected' : '' }}>🇮🇹 Italiano</option>
                            <option value="zh" {{ $settings->language == 'zh' ? 'selected' : '' }}>🇨🇳 中文</option>
                        </select>
                    </div>

                    <!-- Thème Mode -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                            <div>
                                <span class="text-bold" style="font-size: 14px; display: block;">Mode Clair</span>
                                <span class="text-muted" style="font-size: 11px;">Activez pour une interface lumineuse</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="theme_mode" value="1" {{ $settings->theme == 'light' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                    <!-- Accent Color -->
                    <div>
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 15px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Couleur d'accentuation</label>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                            @php
                                $colors = [
                                    'blue' => ['hex' => '#3B82F6', 'name' => 'Bleu'],
                                    'emerald' => ['hex' => '#10B981', 'name' => 'Émeraude'],
                                    'rose' => ['hex' => '#F43F5E', 'name' => 'Rose'],
                                    'amber' => ['hex' => '#F59E0B', 'name' => 'Ambre'],
                                    'indigo' => ['hex' => '#6366F1', 'name' => 'Indigo'],
                                    'purple' => ['hex' => '#A855F7', 'name' => 'Violet'],
                                    'zinc' => ['hex' => '#71717a', 'name' => 'Zinc'],
                                    'orange' => ['hex' => '#f97316', 'name' => 'Orange'],
                                    'cyan' => ['hex' => '#06b6d4', 'name' => 'Cyan'],
                                    'lime' => ['hex' => '#84cc16', 'name' => 'Lime'],
                                    'pink' => ['hex' => '#ec4899', 'name' => 'Pink'],
                                    'teal' => ['hex' => '#14b8a6', 'name' => 'Teal'],
                                ];
                            @endphp
                            @foreach($colors as $key => $color)
                                <label style="position: relative; cursor: pointer;">
                                    <input type="radio" name="accent_color" value="{{ $key }}" style="display: none;" {{ ($settings->accent_color ?? 'blue') == $key ? 'checked' : '' }}>
                                    <div class="accent-option" 
                                        style="width: 100%; aspect-ratio: 1.2; border-radius: 14px; background: {{ $color['hex'] }}; border: 3px solid transparent; transition: 0.3s; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                                        <div class="check-mark" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; opacity: 0; transition: 0.2s;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        <span style="position: absolute; bottom: 5px; left: 0; right: 0; text-align: center; font-size: 8px; font-weight: 700; color: rgba(255,255,255,0.8); text-transform: uppercase;">{{ $color['name'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                        <!-- Notifications Switch -->
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
                            <div>
                                <span class="text-bold" style="font-size: 14px; display: block;">Notifications Push</span>
                                <span class="text-muted" style="font-size: 11px;">Restez informé de vos finances</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="notifications_enabled" value="1" {{ $settings->notifications_enabled ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent"
                        style="width: 100%; border-radius: 18px; margin-top: 30px; padding: 16px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2); border: none;">Enregistrer
                        les préférences</button>
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
                background: rgba(var(--accent-rgb), 0.15) !important;
                border-color: var(--accent-blue) !important;
                box-shadow: 0 8px 15px rgba(var(--accent-rgb), 0.1);
                transform: translateY(-2px);
            }

            input[type="radio"]:checked+.accent-option {
                border-color: white !important;
                transform: scale(1.1);
            }

            input[type="radio"]:checked+.accent-option .check-mark {
                opacity: 1 !important;
            }

            .btn-accent {
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-dark));
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
@endsection