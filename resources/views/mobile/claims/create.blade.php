@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('claims.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Nouvelle Créance</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('claims.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Débiteur (Qui
                        vous doit ?)</label>
                    <input type="text" name="debtor" required placeholder="Ex: Jean Dupont, Société X..."
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <input type="number" name="amount" required placeholder="0"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date
                        d'échéance</label>
                    <input type="date" name="due_date"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <button type="submit" class="btn btn-accent"
                    style="width: 100%; padding: 16px; border-radius: 18px; border: none; font-weight: 700;">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection