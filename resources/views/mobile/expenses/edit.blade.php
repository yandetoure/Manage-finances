@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('expenses.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Modifier Dépense</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Catégorie</label>
                    <input type="text" name="category" value="{{ $expense->category }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <input type="number" name="amount" value="{{ $expense->amount }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date</label>
                    <input type="date" name="date"
                        value="{{ $expense->date ? \Carbon\Carbon::parse($expense->date)->format('Y-m-d') : '' }}"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <input type="checkbox" name="is_recurrent" value="1" id="recurrent" {{ $expense->is_recurrent ? 'checked' : '' }}
                            style="accent-color: #ef4444;" onchange="document.getElementById('frequency-container').style.display = this.checked ? 'block' : 'none'">
                        <label for="recurrent" style="font-size: 14px;">Dépense récurrente</label>
                    </div>

                    <div id="frequency-container" style="{{ $expense->is_recurrent ? 'display: block;' : 'display: none;' }}">
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Fréquence</label>
                        <select name="frequency"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                            <option value="weekly" {{ $expense->frequency == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="monthly" {{ $expense->frequency == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                            <option value="yearly" {{ $expense->frequency == 'yearly' ? 'selected' : '' }}>Annuel</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; background: #ef4444;">Mettre à
                    jour</button>
            </form>
        </div>

        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit"
                style="width: 100%; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer;">Supprimer
                cette dépense</button>
        </form>
    </div>
@endsection