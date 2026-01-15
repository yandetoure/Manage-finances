@extends('layouts.admin')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 30px;">Dashboard Global</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px;">
            <div class="stat-card">
                <p class="text-muted" style="font-size: 14px; margin-bottom: 10px;">Utilisateurs Totaux</p>
                <h3 class="text-bold text-2xl text-blue">{{ $userCount }}</h3>
            </div>
            <div class="stat-card">
                <p class="text-muted" style="font-size: 14px; margin-bottom: 10px;">Revenus Globaux</p>
                <h3 class="text-bold text-2xl text-green">{{ number_format($totalGlobalRevenue, 2, ',', ' ') }} €</h3>
            </div>
            <div class="stat-card">
                <p class="text-muted" style="font-size: 14px; margin-bottom: 10px;">Dettes Totales (En cours)</p>
                <h3 class="text-bold text-2xl text-blue" style="color: var(--danger);">
                    {{ number_format($totalGlobalDebts, 2, ',', ' ') }} €</h3>
            </div>
            <div class="stat-card">
                <p class="text-muted" style="font-size: 14px; margin-bottom: 10px;">Dette Moyenne / User</p>
                <h3 class="text-bold text-2xl text-muted">{{ number_format($avgDebtPerUser, 2, ',', ' ') }} €</h3>
            </div>
        </div>

        <div style="margin-top: 40px;" class="stat-card">
            <h3 class="text-bold" style="margin-bottom: 20px;">Utilisateurs Récents</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th style="padding: 12px; color: var(--text-muted);">Nom</th>
                        <th style="padding: 12px; color: var(--text-muted);">Email</th>
                        <th style="padding: 12px; color: var(--text-muted);">Inscription</th>
                        <th style="padding: 12px; color: var(--text-muted);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- This will be dynamic in the actual users view -->
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 12px;">Admin User</td>
                        <td style="padding: 12px;">admin@manage.com</td>
                        <td style="padding: 12px;">{{ now()->format('d/m/Y') }}</td>
                        <td style="padding: 12px;"><a href="#" class="text-blue">Voir</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection