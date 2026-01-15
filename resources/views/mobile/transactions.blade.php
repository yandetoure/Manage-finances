@extends('layouts.mobile')

@section('content')
<div class="fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 class="text-bold">Transactions</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('revenues.create') }}" class="btn btn-accent" style="padding: 8px 12px; font-size: 12px;">+ Revenu</a>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary" style="padding: 8px 12px; font-size: 12px; background: #ef4444;">+ Dépense</a>
        </div>
    </div>

    @php
        $transactions = collect()
            ->concat($revenues->map(function($r) { 
                $r->type = 'revenue'; 
                $r->tx_date = $r->due_date;
                return $r; 
            }))
            ->concat($expenses->map(function($e) { 
                $e->type = 'expense'; 
                $e->tx_date = $e->date;
                return $e; 
            }))
            ->sortByDesc('tx_date');
    @endphp

    @if($transactions->isEmpty())
        <div class="glass-card" style="text-align: center; padding: 40px 20px;">
            <p class="text-muted">Aucune transaction trouvée.</p>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($transactions as $item)
                <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: {{ $item->type == 'revenue' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $item->type == 'revenue' ? 'var(--accent-blue)' : '#ef4444' }};">
                            @if($item->type == 'revenue')
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-bold" style="font-size: 14px;">{{ $item->type == 'revenue' ? $item->source : $item->category }}</p>
                            <p class="text-muted" style="font-size: 12px;">{{ \Carbon\Carbon::parse($item->tx_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <p class="text-bold" style="color: {{ $item->type == 'revenue' ? 'var(--primary-green)' : '#ef4444' }};">
                        {{ $item->type == 'revenue' ? '+' : '-' }}{{ number_format($item->amount, 0, ',', ' ') }} {{ auth()->user()->currency }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
