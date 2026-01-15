@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 25px;">Paramètres</h2>

        <div class="glass-card">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 15px;">Profil</h3>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                <div
                    style="width: 60px; height: 60px; border-radius: 50%; background: var(--accent-blue); display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: bold;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-bold">{{ auth()->user()->name }}</p>
                    <p class="text-muted" style="font-size: 12px;">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <button class="btn btn-accent" style="width: 100%; border-radius: 8px; font-size: 14px;">Modifier le
                profil</button>
        </div>

        <div class="glass-card">
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
@endsection