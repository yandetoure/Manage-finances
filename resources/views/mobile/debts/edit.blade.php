@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('debts.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Modifier Dette</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('debts.update', $debt->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Créancier</label>
                    <input type="text" name="creditor" value="{{ $debt->creditor }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <input type="number" name="amount" value="{{ $debt->amount }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Statut</label>
                    <select name="status" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                        <option value="pending" {{ $debt->status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ $debt->status == 'paid' ? 'selected' : '' }}>Payée</option>
                        <option value="late" {{ $debt->status == 'late' ? 'selected' : '' }}>En retard</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-accent"
                    style="width: 100%; padding: 16px; border-radius: 18px; border: none; font-weight: 700;">Mettre à
                    jour</button>
            </form>
        </div>

        <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit"
                style="width: 100%; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer;">Supprimer
                cette dette</button>
        </form>
    </div>
@endsection