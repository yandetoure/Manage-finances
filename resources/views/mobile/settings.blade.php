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
                color: var(--text-muted);
                margin: 25px 0 10px 15px;
                opacity: 0.7;
            }



            .settings-group {
                background: var(--card-bg);
                border: 1px solid var(--card-border);
                border-radius: 20px;
                overflow: hidden;
                margin-bottom: 10px;
            }

            .settings-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                border-bottom: 1px solid var(--card-border);
                text-decoration: none;
                color: var(--text-main);
                transition: background 0.2s;
            }

            .settings-row:last-child {
                border-bottom: none;
            }

            .settings-row:active {
                background: rgba(var(--accent-rgb), 0.05);
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
                color: var(--text-muted);
                font-size: 13px;
            }

            .settings-input-ghost {
                background: transparent;
                border: none;
                color: var(--text-main);
                text-align: right;
                font-size: 14px;
                font-family: inherit;
                outline: none;
                padding: 0;
            }

            .settings-select-ghost {
                background: transparent;
                border: none;
                color: var(--text-main);
                font-size: 14px;
                outline: none;
                appearance: none;
                text-align: right;
                padding-right: 5px;
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
                background: var(--accent-blue);
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
                background-color: var(--input-bg);
                transition: .4s;
                border: 1px solid var(--card-border);
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

            .logout-btn {
                background: none;
                border: none;
                color: var(--text-main);
                font-size: 13px;
                text-decoration: underline;
                cursor: pointer;
                opacity: 0.6;
            }
        </style>

        <div class="settings-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="text-bold">Paramètres</h2>
            <span id="save-status" class="text-muted" style="font-size: 11px; display: none;">Sauvegarde...</span>
        </div>

        <form id="settings-form" action="{{ route('settings.update') }}" method="POST">
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
                            required onchange="saveSettings()">
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
                            required onchange="saveSettings()">
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
                            <input type="checkbox" name="theme_mode" value="1" {{ $settings->theme == 'light' ? 'checked' : '' }} onchange="saveSettings()">
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
                    <div style="padding: 16px;">
                        @php
                            $paletteGroups = [
                                'Classiques' => [
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
                                ],
                                'Sombres' => [
                                    'night' => '#334155',
                                    'forest' => '#065f46',
                                    'burgundy' => '#991b1b',
                                    'cacao' => '#78350f',
                                    'midnight' => '#1e1b4b',
                                ],
                                'Doux' => [
                                    'sky' => '#7dd3fc',
                                    'mint' => '#6ee7b7',
                                    'sakura' => '#fbcfe8',
                                    'lavender' => '#ddd6fe',
                                    'sand' => '#fde68a',
                                    'olive' => '#d9f99d',
                                ],
                                'Dégradés' => [
                                    'sunset' => 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)',
                                    'ocean' => 'linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%)',
                                    'cosmic' => 'linear-gradient(135deg, #a855f7 0%, #6366f1 100%)',
                                    'fire' => 'linear-gradient(135deg, #f87171 0%, #7f1d1d 100%)',
                                    'tropical' => 'linear-gradient(135deg, #34d399 0%, #3b82f6 100%)',
                                    'berry' => 'linear-gradient(135deg, #fb7185 0%, #881337 100%)',
                                    'gold' => 'linear-gradient(135deg, #fbbf24 0%, #92400e 100%)',
                                ],
                            ];
                        @endphp

                        @foreach($paletteGroups as $groupName => $colors)
                            <p
                                style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px; {{ $loop->first ? '' : 'margin-top: 15px;' }}">
                                {{ $groupName }}
                            </p>
                            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;">
                                @foreach($colors as $key => $style)
                                    <label class="color-bubble {{ ($settings->accent_color ?? 'blue') == $key ? 'active' : '' }}"
                                        style="background: {{ $style }};" onclick="selectColor(this, '{{ $key }}')">
                                        <input type="radio" name="accent_color" value="{{ $key }}" style="display: none;" {{ ($settings->accent_color ?? 'blue') == $key ? 'checked' : '' }}>
                                        <span class="check">✓</span>
                                    </label>
                                @endforeach
                            </div>
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
                        <select name="currency" class="settings-select-ghost" onchange="saveSettings()">
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
                        <select name="language" class="settings-select-ghost" onchange="saveSettings()">
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
                            <input type="checkbox" name="notifications_enabled" value="1" {{ $settings->notifications_enabled ? 'checked' : '' }} onchange="saveSettings()">
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


        </form>

        <div style="margin-top: 25px; text-align: center; opacity: 0.5;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
            <p style="font-size: 10px; margin-top: 15px;">Version 1.2.0 - Manage Premium</p>
        </div>

        <script>
            // Store initial theme values to avoid unnecessary reloads
            let currentTheme = '{{ $settings->theme ?? 'dark' }}';
            let currentAccent = '{{ $settings->accent_color ?? 'blue' }}';

            function selectColor(el, key) {
                document.querySelectorAll('.color-bubble').forEach(b => b.classList.remove('active'));
                el.classList.add('active');
                el.querySelector('input').checked = true;
                saveSettings();
            }

            async function saveSettings() {
                const form = document.getElementById('settings-form');
                const status = document.getElementById('save-status');
                const formData = new FormData(form);

                status.style.display = 'block';
                status.style.color = 'var(--text-muted)';
                status.innerText = 'Sauvegarde...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    if (response.ok) {
                        status.innerText = 'Enregistré ↑';

                        const newAccent = formData.get('accent_color');
                        const newTheme = formData.has('theme_mode') ? 'light' : 'dark';

                        setTimeout(() => {
                            // Only reload if a visual theme-changing setting was actually modified
                            if (newAccent !== currentAccent || newTheme !== currentTheme) {
                                window.location.reload();
                            } else {
                                status.style.display = 'none';
                            }
                        }, 1000);
                    } else {
                        const errorData = await response.json();
                        status.innerText = 'Erreur ❌';
                        status.style.color = '#ef4444';
                        console.error('Validation failed:', errorData);
                    }
                } catch (error) {
                    console.error('Error saving settings:', error);
                    status.innerText = 'Erreur ❌';
                    status.style.color = '#ef4444';
                }
            }
        </script>
@endsection