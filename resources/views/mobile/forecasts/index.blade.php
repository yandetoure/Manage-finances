@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Prévisions</h2>
            <a href="{{ route('forecasts.create') }}" class="btn btn-accent">+ Prévoir</a>
        </div>

        @if($forecasts->isEmpty())
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucune prévision pour le moment.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($forecasts as $forecast)
                    <div class="glass-card" style="padding: 20px;">
                        <h3 class="text-bold" style="font-size: 16px; margin-bottom: 15px;">
                            {{ \Carbon\Carbon::createFromDate($forecast->year, $forecast->month, 1)->translatedFormat('F Y') }}
                        </h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <p class="text-muted" style="font-size: 11px;">Revenus Est.</p>
                                <p class="text-bold" style="color: var(--accent-blue);">
                                    +{{ number_format($forecast->estimated_revenue, 0, ',', ' ') }}</p>
                            </div>
                            <div>
                                <p class="text-muted" style="font-size: 11px;">Dépenses Est.</p>
                                <p class="text-bold" style="color: #ef4444;">
                                    -{{ number_format($forecast->estimated_expenses, 0, ',', ' ') }}</p>
                            </div>
                        </div>
                        <hr style="opacity: 0.05; margin: 15px 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted" style="font-size: 12px;">Solde Projeté</span>
                            <span class="text-bold"
                                style="color: var(--primary-green);">{{ number_format($forecast->estimated_revenue - $forecast->estimated_expenses, 0, ',', ' ') }}
                                {{ auth()->user()->currency }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection