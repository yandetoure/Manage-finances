@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Ma Vision Globale</h2>
            
            <form action="{{ route('analytics') }}" method="GET" id="filterForm">
                <select name="month" onchange="document.getElementById('filterForm').submit()" 
                    style="background: var(--input-bg); border: 1px solid var(--card-border); color: var(--text-main); padding: 8px 12px; border-radius: 12px; font-size: 14px; outline: none;">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="year" onchange="document.getElementById('filterForm').submit()"
                    style="background: var(--input-bg); border: 1px solid var(--card-border); color: var(--text-main); padding: 8px 12px; border-radius: 12px; font-size: 14px; outline: none;">
                    @foreach(range(now()->year - 2, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Revenus</p>
                <h3 class="text-bold" style="font-size: 20px; color: var(--primary-green);">{{ number_format($totalRevenue, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Dépenses</p>
                <h3 class="text-bold" style="font-size: 20px; color: #ef4444;">{{ number_format($totalExpenses, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Épargne</p>
                <h3 class="text-bold" style="font-size: 20px; color: var(--accent-blue);">{{ number_format($totalSavings, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Dettes Créées</p>
                <h3 class="text-bold" style="font-size: 20px; color: #f59e0b;">{{ number_format($totalDebts, 0, ',', ' ') }}</h3>
            </div>
        </div>

        <div class="glass-card" style="padding: 20px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="text-bold" style="font-size: 16px;">Résumé de la période</h3>
                <span class="text-muted" style="font-size: 11px;">{{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</span>
            </div>
            
            </div>
        </div>

        <!-- Charts Section -->
        <div class="glass-card" style="padding: 20px; margin-bottom: 25px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Répartition des Dépenses</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div id="categoryLegend" style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                @foreach($expensesByCategory as $cat)
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $cat['color'] }};"></div>
                        <span style="font-size: 11px; opacity: 0.8;">{{ $cat['name'] }}</span>
                        <span style="font-size: 11px; font-weight: 700; margin-left: auto;">{{ number_format($cat['amount'], 0, ',', ' ') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="glass-card" style="padding: 20px; margin-bottom: 25px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Évolution (6 mois)</h3>
            <div style="height: 250px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="glass-card" style="padding: 20px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="text-bold" style="font-size: 16px;">Résumé de la période</h3>
                <span class="text-muted" style="font-size: 11px;">{{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</span>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                    <span class="text-muted" style="font-size: 13px;">Solde Net (Revenus - Dépenses)</span>
                    <span class="text-bold {{ $balance >= 0 ? 'text-green' : 'text-bold' }}" style="font-size: 13px; color: {{ $balance >= 0 ? 'var(--primary-green)' : '#ef4444' }};">
                        {{ $balance >= 0 ? '+' : '' }} {{ number_format($balance, 0, ',', ' ') }} {{ auth()->user()->currency }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border);">
                    <span class="text-muted" style="font-size: 13px;">Taux d'épargne</span>
                    <span class="text-bold" style="font-size: 13px;">
                        @if($totalRevenue > 0)
                            {{ round(($totalSavings / $totalRevenue) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
                    const categoryData = @json($expensesByCategory);
                    
                    new Chart(ctxCategory, {
                        type: 'doughnut',
                        data: {
                            labels: categoryData.map(c => c.name),
                            datasets: [{
                                data: categoryData.map(c => c.amount),
                                backgroundColor: categoryData.map(c => c.color),
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const val = context.raw;
                                            return ` ${context.label}: ${val.toLocaleString()} {{ auth()->user()->currency }}`;
                                        }
                                    }
                                }
                            },
                            cutout: '75%'
                        }
                    });

                    const ctxTrend = document.getElementById('trendChart').getContext('2d');
                    const trendData = @json($sixMonthTrend);
                    const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-blue').trim();

                    new Chart(ctxTrend, {
                        type: 'line',
                        data: {
                            labels: trendData.map(d => d.month),
                            datasets: [
                                {
                                    label: 'Revenus',
                                    data: trendData.map(d => d.revenue),
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 0
                                },
                                {
                                    label: 'Dépenses',
                                    data: trendData.map(d => d.expense),
                                    borderColor: accentColor || '#ef4444',
                                    backgroundColor: (accentColor || '#ef4444') + '1A',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim(),
                                        usePointStyle: true,
                                        boxWidth: 6,
                                        padding: 20
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    display: false,
                                    beginAtZero: true
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim()
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
            
            <a href="{{ route('home') }}" class="btn btn-primary" style="width: 100%; margin-top: 20px; text-align: center; border-radius: 18px; padding: 16px; font-size: 15px; font-weight: 700;">Retour au Dashboard</a>
        </div>
    </div>
@endsection
