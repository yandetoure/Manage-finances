@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('forecasts.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Modifier Prévision</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('forecasts.update', $forecast->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Mois</label>
                        <select name="month" required
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $forecast->month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Année</label>
                        <select name="year" required
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                            @foreach(range(date('Y'), date('Y') + 2) as $y)
                                <option value="{{ $y }}" {{ $forecast->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Revenus Estimés
                        (FCFA)</label>
                    <input type="number" name="estimated_revenue" value="{{ $forecast->estimated_revenue }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Dépenses Estimées
                        (FCFA)</label>
                    <input type="number" name="estimated_expenses" value="{{ $forecast->estimated_expenses }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <button type="submit" class="btn btn-accent" style="width: 100%;">Mettre à jour</button>
            </form>
        </div>

        <form action="{{ route('forecasts.destroy', $forecast->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit"
                style="width: 100%; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer;">Supprimer
                cette prévision</button>
        </form>
    </div>
@endsection