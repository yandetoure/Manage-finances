@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Dépenses</h2>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary" style="background: #ef4444;">+ Ajouter</a>
        </div>

        @if($expenses->isEmpty())
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucune dépense enregistrée.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($expenses as $expense)
                    <div class="glass-card"
                        style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
                        <div>
                            <p class="text-bold" style="font-size: 14px;">{{ $expense->category }}</p>
                            <p class="text-muted" style="font-size: 12px;">
                                {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                            </p>
                        </div>
                        <p class="text-bold" style="color: #ef4444;">
                            -{{ number_format($expense->amount, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection