@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <!-- Main Balance Card -->
        <div class="glass-card" style="background: var(--accent-blue); border: none; color: white;">
            <p style="opacity: 0.8; font-size: 14px;">{{ __('Solde Total') }}</p>
            <h2 class="text-bold" style="font-size: 32px; margin-top: 5px;">{{ number_format($balance, 0, ',', ' ') }}
                {{ auth()->user()->currency }}
            </h2>
            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <span
                    style="background: rgba(255, 255, 255, 0.2); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">+2.4%
                    {{ __('ce mois') }}</span>
            </div>
        </div>

        <!-- Summary Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <a href="{{ route('claims.index') }}" class="glass-card"
                style="margin-bottom: 0; text-decoration: none; border-left: 4px solid var(--accent-blue);">
                <p class="text-muted" style="font-size: 12px;">{{ __('Créances (Reste)') }}</p>
                <p class="text-bold" style="color: var(--accent-blue);">{{ number_format($totalClaims, 0, ',', ' ') }}
                    {{ auth()->user()->currency }}
                </p>
            </a>
            <a href="{{ route('debts.index') }}" class="glass-card" style="margin-bottom: 0; text-decoration: none;">
                <p class="text-muted" style="font-size: 12px;">{{ __('Dettes (Reste)') }}</p>
                <p class="text-bold text-red">{{ number_format($totalDebts, 0, ',', ' ') }} {{ auth()->user()->currency }}
                </p>
            </a>
        </div>

        <!-- Monthly Graph Placeholder (Using Chart.js) -->
        <div class="glass-card" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <p class="text-bold">{{ __('Activité Mensuelle') }}</p>
                <span class="text-muted" style="font-size: 12px;">{{ now()->translatedFormat('F') }}</span>
            </div>
            <canvas id="balanceChart" height="200"></canvas>
        </div>

        <!-- Rapid Actions -->
        <div style="margin-top: 20px; padding-bottom: 40px;">
            <p class="text-bold" style="margin-bottom: 15px;">{{ __('Actions Rapides') }}</p>
            <div style="display: flex; overflow-x: auto; gap: 15px; padding-bottom: 10px;">
                <a href="{{ route('revenues.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--accent-blue);">
                        📥</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Ajouter Revenu') }}</span>
                </a>
                <a href="{{ route('expenses.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(239, 68, 68, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: #ef4444;">
                        📤</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Dépense') }}</span>
                </a>
                <a href="{{ route('debts.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--accent-blue);">
                        💸</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Rembourser') }}</span>
                </a>
                <a href="{{ route('claims.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--accent-blue);">
                        💰</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Prêter') }}</span>
                </a>
                <a href="{{ route('savings.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--accent-blue);">
                        🏦</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Épargner') }}</span>
                </a>
                <a href="{{ route('forecasts.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none; border: 1px solid var(--card-border);">
                    <div
                        style="width: 45px; height: 45px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 8px; color: var(--accent-blue);">
                        📊</div>
                    <span class="text-muted" style="font-size: 12px;">{{ __('Prévoir') }}</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('balanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1', '5', '10', '15', '20', '25', '30'],
                datasets: [{
                    label: "{{ __('Solde') }}",
                    data: [1200, 1500, 1300, 2100, 1800, 2400, 2540],
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--accent-blue').trim() || '#3B82F6',
                    backgroundColor: `rgba(${getComputedStyle(document.documentElement).getPropertyValue('--accent-rgb').trim() || '59, 130, 246'}, 0.1)`,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                }, {
                    label: "{{ __('Dépenses') }}",
                    data: [800, 900, 1100, 950, 1200, 1050, 1100],
                    borderColor: '#94A3B8',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    borderDash: [5, 5],
                    pointRadius: 0
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim() || '#94A3B8'
                        }
                    }
                }
            }
        });
    </script>
@endsection