@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Vision Globale</h2>

            <form action="{{ route('admin.dashboard') }}" method="GET" id="filterForm">
                <select name="month" onchange="document.getElementById('filterForm').submit()"
                    style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 8px 12px; border-radius: 12px; font-size: 14px; outline: none;">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="year" onchange="document.getElementById('filterForm').submit()"
                    style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 8px 12px; border-radius: 12px; font-size: 14px; outline: none;">
                    @foreach(range(now()->year - 2, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Revenus
                    (Mois)</p>
                <h3 class="text-bold" style="font-size: 20px; color: var(--primary-green);">
                    {{ number_format($totalGlobalRevenue, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Dépenses
                    (Mois)</p>
                <h3 class="text-bold" style="font-size: 20px; color: #ef4444;">
                    {{ number_format($totalGlobalExpenses, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Épargne
                    (Mois)</p>
                <h3 class="text-bold" style="font-size: 20px; color: var(--accent-blue);">
                    {{ number_format($totalGlobalSavings, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Dettes Créées
                </p>
                <h3 class="text-bold" style="font-size: 20px; color: #f59e0b;">
                    {{ number_format($totalGlobalDebts, 0, ',', ' ') }}</h3>
            </div>
        </div>

        <div class="glass-card" style="padding: 20px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="text-bold" style="font-size: 16px;">Statistiques Platform</h3>
                <span class="text-muted" style="font-size: 11px;">{{ $userCount }} Utilisateurs</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div
                    style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="text-muted" style="font-size: 13px;">Dette Moyenne / User</span>
                    <span class="text-bold"
                        style="font-size: 13px;">{{ number_format($avgDebtPerUser, 0, ',', ' ') }}</span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span class="text-muted" style="font-size: 13px;">Marge Brut (Global)</span>
                    <span class="text-bold" style="font-size: 13px; color: var(--primary-green);">+
                        {{ number_format($totalGlobalRevenue - $totalGlobalExpenses, 0, ',', ' ') }}</span>
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn btn-primary"
                style="width: 100%; margin-top: 20px; text-align: center; border-radius: 12px; font-size: 14px;">Gérer les
                utilisateurs</a>
        </div>
    </div>
@endsection