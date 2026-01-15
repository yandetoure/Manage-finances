@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('savings.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Modifier Projet d'Épargne</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('savings.update', $saving->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Nom du
                        projet</label>
                    <input type="text" name="target_name" value="{{ $saving->target_name }}" required
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Objectif
                            (FCFA)</label>
                        <input type="number" name="target_amount" value="{{ $saving->target_amount }}" required
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                    </div>
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Déjà
                            épargné</label>
                        <input type="number" name="current_amount" value="{{ $saving->current_amount }}" required
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date
                        limite</label>
                    <input type="date" name="deadline"
                        value="{{ $saving->deadline ? \Carbon\Carbon::parse($saving->deadline)->format('Y-m-d') : '' }}"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <button type="submit" class="btn btn-accent" style="width: 100%; background: var(--primary-green);">Mettre à
                    jour</button>
            </form>
        </div>

        <form action="{{ route('savings.destroy', $saving->id) }}" method="POST" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit"
                style="width: 100%; background: transparent; border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 12px; font-weight: 600; cursor: pointer;">Supprimer
                ce projet</button>
        </form>
    </div>
@endsection