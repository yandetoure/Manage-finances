@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('revenues.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Nouveau Revenu</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('revenues.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Source du
                        revenu</label>
                    <input type="text" name="source" required placeholder="Ex: Salaire, Freelance..."
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date
                        prévue</label>
                    <input type="date" name="due_date"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_recurrent" value="1" id="recurrent"
                        style="accent-color: var(--primary-green);">
                    <label for="recurrent" style="font-size: 14px;">Revenu récurrent</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection