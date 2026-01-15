@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('expenses.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Nouvelle Dépense</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Catégorie</label>
                    <input type="text" name="category" required placeholder="Ex: Loyer, Nourriture..."
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <input type="number" name="amount" required placeholder="0"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_recurrent" value="1" id="recurrent" style="accent-color: #ef4444;">
                    <label for="recurrent" style="font-size: 14px;">Dépense récurrente</label>
                </div>

                <div style="margin-bottom: 25px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Fréquence</label>
                    <select name="frequency"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                        <option value="monthly">Mensuel</option>
                        <option value="weekly">Hebdomadaire</option>
                        <option value="yearly">Annuel</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; background: #ef4444;">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection