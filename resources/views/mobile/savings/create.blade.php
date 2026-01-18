@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <style>
            .category-radio:checked+.category-item {
                background: rgba(59, 130, 246, 0.1) !important;
                border-color: #3b82f6 !important;
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);
            }

            ::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('savings.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Nouveau Projet d'Épargne</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('savings.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label class="text-muted"
                        style="font-size: 12px; display: block; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Catégorie</label>
                    <div
                        style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 15px; scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($categories as $category)
                            <label style="flex-shrink: 0; cursor: pointer;">
                                <input type="radio" name="category_id" value="{{ $category->id }}" required
                                    style="display: none;" class="category-radio">
                                <div class="category-item"
                                    style="text-align: center; width: 75px; height: 85px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; transition: 0.2s;">
                                    <span style="font-size: 24px;">{{ $category->icon }}</span>
                                    <span style="font-size: 10px; font-weight: 600;">{{ $category->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Nom du
                        projet</label>
                    <input type="text" name="target_name" required placeholder="Ex: Voyage, Voiture, Mariage..."
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Objectif
                            ({{ auth()->user()->currency }})</label>
                        <input type="number" name="target_amount" required placeholder="0"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                    </div>
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Déjà
                            épargné</label>
                        <input type="number" name="current_amount" value="0"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 8px;">Date limite
                        (optionnel)</label>
                    <input type="date" name="deadline"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                </div>

                <button type="submit" class="btn btn-accent"
                    style="width: 100%; border: none; padding: 16px; border-radius: 18px;">Lancer
                    le projet</button>
            </form>
        </div>
    </div>
@endsection