@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 25px;">Paramètres</h2>

        <!-- Profil Section -->
        <div class="glass-card" style="margin-bottom: 20px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 15px;">Profil</h3>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--accent-blue); display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: bold;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-bold">{{ auth()->user()->name }}</p>
                    <p class="text-muted" style="font-size: 12px;">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <button class="btn btn-accent" style="width: 100%; border-radius: 8px; font-size: 14px;">Modifier le profil</button>
        </div>

        <!-- Modules Financiers Section -->
        <div class="glass-card" style="margin-bottom: 20px; padding: 20px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Modules Financiers</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <a href="{{ route('revenues.index') }}" class="module-btn" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2);">
                    <div class="module-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">📈</div>
                    <span class="module-label">Revenus</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="module-btn" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2);">
                    <div class="module-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">📉</div>
                    <span class="module-label">Dépenses</span>
                </a>
                <a href="{{ route('debts.index') }}" class="module-btn" style="background: rgba(168, 85, 247, 0.05); border: 1px solid rgba(168, 85, 247, 0.2);">
                    <div class="module-icon" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">💸</div>
                    <span class="module-label">Dettes</span>
                </a>
                <a href="{{ route('claims.index') }}" class="module-btn" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <div class="module-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">🤝</div>
                    <span class="module-label">Créances</span>
                </a>
                <a href="{{ route('savings.index') }}" class="module-btn" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2);">
                    <div class="module-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">💰</div>
                    <span class="module-label">Épargne</span>
                </a>
                <a href="{{ route('forecasts.index') }}" class="module-btn" style="background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2);">
                    <div class="module-icon" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">🔮</div>
                    <span class="module-label">Prévisions</span>
                </a>
            </div>
        </div>

        <!-- Support & Sécurité Section -->
        <div class="glass-card" style="margin-bottom: 20px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Support & Sécurité</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Changer le mot de passe</span>
                    <span class="text-muted">→</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Notifications</span>
                    <input type="checkbox" checked style="accent-color: var(--primary-green);">
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Mode Sombre</span>
                    <input type="checkbox" checked disabled style="accent-color: var(--primary-green);">
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('admin'))
            <div style="margin-top: 20px;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"
                    style="width: 100%; background: #1e293b; border: 1px solid var(--accent-blue);">Accéder à l'Admin</a>
            </div>
        @endif

        <div style="margin-top: 30px; text-align: center;">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="text-muted"
                    style="background: none; border: none; font-size: 14px; text-decoration: underline; cursor: pointer;">Déconnexion</button>
            </form>
            <p class="text-muted" style="font-size: 10px; margin-top: 20px;">Version 1.0.0 - Manage Financial SaaS</p>
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
    </style>
@endsection