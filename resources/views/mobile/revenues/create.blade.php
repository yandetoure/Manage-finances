@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <style>
            .category-radio:checked+.category-item {
                background: rgba(16, 185, 129, 0.15) !important;
                border-color: #10b981 !important;
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(16, 185, 129, 0.1);
            }

            .category-radio:checked+.category-item span:first-child {
                transform: scale(1.1);
            }

            .input-modern:focus {
                background: rgba(255, 255, 255, 0.08) !important;
                border-color: #10b981 !important;
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
            }

            .switch {
                position: relative;
                display: inline-block;
                width: 46px;
                height: 24px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(255, 255, 255, 0.1);
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .4s;
            }

            input:checked+.slider {
                background-color: #10b981;
            }

            input:checked+.slider:before {
                transform: translateX(22px);
            }

            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }

            ::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('revenues.index') }}" style="text-decoration: none; font-size: 24px;">←</a>
            <h2 class="text-bold">Nouveau Revenu</h2>
        </div>

        <div class="glass-card">
            <form action="{{ route('revenues.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 25px;">
                    <label class="text-muted"
                        style="font-size: 11px; display: block; margin-bottom: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Catégorie</label>
                    <div
                        style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 15px; scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($categories as $category)
                            <label style="flex-shrink: 0; cursor: pointer;">
                                <input type="radio" name="category_id" value="{{ $category->id }}" required
                                    style="display: none;" class="category-radio">
                                <div class="category-item"
                                    style="text-align: center; width: 80px; height: 90px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                                    <span
                                        style="font-size: 28px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">{{ $category->icon }}</span>
                                    <span style="font-size: 10px; font-weight: 700; opacity: 0.8;">{{ $category->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted"
                        style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Source
                        du revenu</label>
                    <div style="position: relative;">
                        <input type="text" name="source" required placeholder="Ex: Salaire, Freelance..."
                            class="input-modern"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 14px 14px 14px 45px; color: white; outline: none; transition: all 0.3s;">
                        <div style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); opacity: 0.5;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted"
                        style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Montant
                        ({{ auth()->user()->currency }})</label>
                    <div style="position: relative;">
                        <input type="number" step="0.01" name="amount" required placeholder="0.00" class="input-modern"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 14px 14px 14px 45px; color: white; outline: none; transition: all 0.3s; font-weight: 700; font-size: 18px;">
                        <div
                            style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); opacity: 0.5; font-size: 18px;">
                            💰
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted"
                        style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Date
                        prévue</label>
                    <div style="position: relative;">
                        <input type="date" name="due_date" class="input-modern"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 14px 14px 14px 45px; color: white; outline: none; transition: all 0.3s;">
                        <div style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); opacity: 0.5;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    style="margin-bottom: 25px; background: rgba(255,255,255,0.02); border-radius: 20px; padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div
                                style="width: 35px; height: 35px; background: rgba(16, 185, 129, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 2v6h-6" />
                                    <path d="M3 12a9 9 0 0 1 15-6.7L21 8" />
                                    <path d="M3 22v-6h6" />
                                    <path d="M21 12a9 9 0 0 1-15 6.7L3 16" />
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 14px; font-weight: 600; margin: 0;">Revenu récurrent</p>
                                <p class="text-muted" style="font-size: 11px; margin: 0;">S'ajoute automatiquement chaque
                                    période</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="is_recurrent" value="1" id="recurrent"
                                onchange="document.getElementById('frequency-container').style.display = this.checked ? 'block' : 'none'">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div id="frequency-container"
                        style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.05);">
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Fréquence</label>
                        <select name="frequency" class="input-modern"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; outline: none;">
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly" selected>Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 16px; border-radius: 18px; font-weight: 700; font-size: 16px; border: none;">
                    Enregistrer le revenu
                </button>
            </form>
        </div>
    </div>
@endsection