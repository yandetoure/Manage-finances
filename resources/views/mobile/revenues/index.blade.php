@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="text-bold">Mes Revenus</h2>
            <a href="{{ route('revenues.create') }}" class="btn btn-primary"
                style="padding: 8px 16px; border-radius: 50px;">+ Nouveau</a>
        </div>

        @forelse($revenues as $revenue)
            <div class="glass-card fade-in" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p class="text-bold">{{ $revenue->source }}</p>
                    <p class="text-muted" style="font-size: 12px;">{{ $revenue->due_date ?? 'Pas de date' }}</p>
                </div>
                <div style="text-align: right;">
                    <p class="text-bold text-green">+ {{ number_format($revenue->amount, 2, ',', ' ') }} €</p>
                    @if($revenue->is_recurrent)
                        <span
                            style="font-size: 10px; background: rgba(59, 130, 246, 0.2); color: var(--accent-blue); padding: 2px 6px; border-radius: 10px;">Récurrent</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucun revenu enregistré.</p>
            </div>
        @endforelse
    </div>
@endsection