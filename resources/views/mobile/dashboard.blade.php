@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <!-- Main Balance Card -->
        <div class="glass-card" style="background: linear-gradient(135deg, var(--accent-blue), #1e293b); border: none;">
            <p class="text-muted" style="color: rgba(255,255,255,0.7);">Solde Total</p>
            <h2 class="text-bold" style="font-size: 32px; margin-top: 5px;">{{ number_format($balance, 2, ',', ' ') }} €
            </h2>
            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <span class="text-green"
                    style="background: rgba(16, 185, 129, 0.2); padding: 4px 10px; border-radius: 20px; font-size: 12px;">+2.4%
                    ce mois</span>
            </div>
        </div>

        <!-- Summary Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="glass-card" style="margin-bottom: 0;">
                <p class="text-muted" style="font-size: 12px;">Épargne</p>
                <p class="text-bold text-blue">{{ number_format($totalSavings, 2, ',', ' ') }} €</p>
            </div>
            <div class="glass-card" style="margin-bottom: 0;">
                <p class="text-muted" style="font-size: 12px;">Dettes</p>
                <p class="text-bold text-green">{{ number_format($totalDebts, 2, ',', ' ') }} €</p>
            </div>
        </div>

        <!-- Monthly Graph Placeholder (Using Chart.js) -->
        <div class="glass-card" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <p class="text-bold">Activité Mensuelle</p>
                <span class="text-muted" style="font-size: 12px;">Octobre</span>
            </div>
            <canvas id="balanceChart" height="200"></canvas>
        </div>

        <!-- Rapid Actions -->
        <div style="margin-top: 20px; padding-bottom: 40px;">
            <p class="text-bold" style="margin-bottom: 15px;">Actions Rapides</p>
            <div style="display: flex; overflow-x: auto; gap: 15px; padding-bottom: 10px;">
                <a href="{{ route('revenues.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                    <span style="font-size: 24px; margin-bottom: 5px;">📥</span>
                    <span class="text-muted" style="font-size: 12px;">Ajouter Revenu</span>
                </a>
                <a href="{{ route('expenses.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                    <span style="font-size: 24px; margin-bottom: 5px;">📤</span>
                    <span class="text-muted" style="font-size: 12px;">Dépense</span>
                </a>
                <a href="{{ route('debts.create') }}" class="glass-card"
                    style="min-width: 120px; display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                    <span style="font-size: 24px; margin-bottom: 5px;">💸</span>
                    <span class="text-muted" style="font-size: 12px;">Rembourser</span>
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
                    label: 'Solde',
                    data: [1200, 1500, 1300, 2100, 1800, 2400, 2540],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                }, {
                    label: 'Dépenses',
                    data: [800, 900, 1100, 950, 1200, 1050, 1100],
                    borderColor: '#3B82F6',
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
                    x: { grid: { display: false }, ticks: { color: '#94A3B8' } }
                }
            }
        });
    </script>
@endsection