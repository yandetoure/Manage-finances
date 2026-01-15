@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('revenues.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Modifier Revenu</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('revenues.update', $revenue->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Source du revenu</label>
                    <input type="text" name="source" value="{{ $revenue->source }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Montant (FCFA)</label>
                    <input type="number" name="amount" value="{{ $revenue->amount }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date prévue</label>
                    <input type="date" name="due_date" value="{{ $revenue->due_date ? \Carbon\Carbon::parse($revenue->due_date)->format('Y-m-d') : '' }}"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_recurrent" value="1" id="recurrent" {{ $revenue->is_recurrent ? 'checked' : '' }}
                        style="accent-color: var(--primary-green);">
                    <label for="recurrent" style="font-size: 14px;">Revenu récurrent</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Mettre à jour</button>
            </form>
        </div>

        <form action="{{ route('revenues.destroy', $revenue->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit" style="width: 100%; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer;">Supprimer ce revenu</button>
        </form>
    </div>
@endsection
