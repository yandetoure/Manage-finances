@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="text-bold">Mes Dettes</h2>
            <a href="{{ route('debts.create') }}" class="btn btn-accent" style="padding: 8px 16px; border-radius: 50px;">+
                Nouvelle</a>
        </div>

        @forelse($debts as $debt)
            <div class="glass-card fade-in" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p class="text-bold">{{ $debt->creditor }}</p>
                    <p class="text-muted" style="font-size: 12px;">Échéance: {{ $debt->due_date ?? 'N/A' }}</p>
                </div>
                <div style="text-align: right;">
                    <p class="text-bold text-blue">{{ number_format($debt->amount, 2, ',', ' ') }} €</p>
                    <span
                        style="font-size: 10px; background: {{ $debt->status == 'paid' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; color: {{ $debt->status == 'paid' ? 'var(--primary-green)' : 'var(--danger)' }}; padding: 2px 6px; border-radius: 10px;">
                        {{ ucfirst($debt->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucune dette enregistrée.</p>
            </div>
        @endforelse
    </div>
@endsection