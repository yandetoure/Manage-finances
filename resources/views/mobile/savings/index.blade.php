@extends('layouts.mobile')

@section('content')
    <div class="fade-in" x-data="{ 
        menuOpen: false, 
        activeSaving: null, 
        contributionOpen: false,
        historyOpen: false,
        openMenu(saving) {
            this.activeSaving = saving;
            this.menuOpen = true;
            this.historyOpen = false;
        },
        closeMenu() {
            this.menuOpen = false;
            this.contributionOpen = false;
        }
    }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Mon Épargne</h2>
            <a href="{{ route('savings.create') }}" class="btn btn-accent" style="padding: 8px 16px; border-radius: 50px;">+
                Nouveau</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($savings as $saving)
                @php
                    $percentage = min(100, ($saving->current_amount / $saving->target_amount) * 100);
                @endphp
                <div class="glass-card" @click="openMenu({{ Js::from($saving) }})"
                    style="padding: 20px; cursor: pointer; transition: transform 0.2s;"
                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5" />
                                    <path d="M12 19V5" />
                                </svg>
                            </div>
                            <span class="text-bold" style="font-size: 15px;">{{ $saving->target_name }}</span>
                        </div>
                        <span
                            style="font-size: 11px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 2px 8px; border-radius: 6px;">{{ number_format($percentage, 0) }}%</span>
                    </div>

                    <div
                        style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.05);">
                        <div
                            style="width: {{ $percentage }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #10b981); border-radius: 10px; transition: width 0.5s ease-out;">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                        <span
                            style="font-size: 15px; color: #10b981; font-weight: 700;">{{ number_format($saving->current_amount, 0, ',', ' ') }}
                            FCFA</span>
                        <span style="font-size: 11px; color: var(--text-muted);">Cible:
                            {{ number_format($saving->target_amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            @empty
                <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                    <p class="text-muted">Aucun projet d'épargne en cours.</p>
                </div>
            @endforelse
        </div>

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen" class="overlay" @click="closeMenu()" x-transition:enter="fade-in"
            x-transition:leave="fade-out" style="display: none;"></div>

        <div x-show="menuOpen" class="bottom-sheet" x-transition:enter="slide-up" x-transition:leave="slide-down"
            style="display: none; background: rgba(23, 23, 23, 0.98); backdrop-filter: blur(25px); border-top-left-radius: 35px; border-top-right-radius: 35px; padding: 25px 20px 120px; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; border-top: 1px solid rgba(255,255,255,0.12); height: auto; max-height: 85vh; overflow-y: auto;">
            <template x-if="activeSaving">
                <div>
                    <div
                        style="width: 40px; height: 5px; background: rgba(255,255,255,0.25); border-radius: 3px; margin: 0 auto 20px;">
                    </div>

                    <div style="text-align: center; margin-bottom: 25px;">
                        <h3 class="text-bold" style="font-size: 22px; margin-bottom: 5px;"
                            x-text="activeSaving.target_name"></h3>
                        <p style="color: #10b981; font-weight: 700; font-size: 18px;"
                            x-text="new Intl.NumberFormat().format(activeSaving.current_amount) + ' FCFA'"></p>
                        <p class="text-muted" style="font-size: 13px; margin-top: 5px;">
                            Reste à épargner: <span style="color: #3b82f6; font-weight: 600;"
                                x-text="new Intl.NumberFormat().format(activeSaving.target_amount - activeSaving.current_amount) + ' FCFA'"></span>
                        </p>
                    </div>

                    <!-- Main Actions Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px;">
                        <button @click="contributionOpen = true" class="action-card"
                            style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); grid-column: span 2;">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">💰</span>
                            <span style="font-weight: 600; color: #10b981;">Épargner</span>
                        </button>

                        <button @click="historyOpen = !historyOpen" class="action-card"
                            :style="historyOpen ? 'background: rgba(255,255,255,0.15);' : ''">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">📜</span>
                            <span style="font-weight: 600;">Historique</span>
                        </button>

                        <a :href="'/savings/' + activeSaving.id + '/edit'" class="action-card"
                            style="text-decoration: none; color: white;">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">✏️</span>
                            <span style="font-weight: 600;">Modifier</span>
                        </a>
                    </div>

                    <!-- History Section -->
                    <div x-show="historyOpen" x-transition:enter="fade-in"
                        style="background: rgba(255,255,255,0.03); border-radius: 20px; padding: 20px; margin-top: 10px;">
                        <h4 class="text-bold" style="font-size: 15px; margin-bottom: 15px;">Historique des économies</h4>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <template x-if="activeSaving.contributions && activeSaving.contributions.length > 0">
                                <template x-for="contribution in activeSaving.contributions" :key="contribution.id">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <div>
                                            <p class="text-bold" style="font-size: 14px;"
                                                x-text="new Intl.NumberFormat().format(contribution.amount) + ' FCFA'"></p>
                                            <p class="text-muted" style="font-size: 11px;"
                                                x-text="new Date(contribution.contribution_date).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'})">
                                            </p>
                                        </div>
                                        <span
                                            style="font-size: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 2px 8px; border-radius: 20px;">Ajouté</span>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!activeSaving.contributions || activeSaving.contributions.length === 0">
                                <div style="text-align: center; padding: 20px;">
                                    <p class="text-muted" style="font-size: 13px;">Aucune économie trouvée</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Contribution Modal -->
        <div x-show="contributionOpen" class="overlay" style="z-index: 1001; background: rgba(0,0,0,0.85); display: none;"
            x-transition:enter="fade-in" x-transition:leave="fade-out">
            <div class="glass-card" @click.stop
                style="max-width: 90%; width: 350px; margin: auto; padding: 30px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.15);">
                <h3 class="text-bold" style="margin-bottom: 5px; font-size: 18px;">Ajouter à l'épargne</h3>
                <p class="text-muted" style="font-size: 12px; margin-bottom: 25px;">Économisez pour <span
                        x-text="activeSaving?.target_name" style="color: white; font-weight: 600;"></span></p>

                <form action="{{ route('savings.contribute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="saving_id" :value="activeSaving?.id">

                    <div style="margin-bottom: 18px;">
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Montant</label>
                        <input type="number" name="amount" required step="0.01" class="input-modern"
                            style="width: 100%; font-size: 16px; font-weight: 600;" placeholder="0 FCFA">
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Date</label>
                        <input type="date" name="contribution_date" required value="{{ date('Y-m-d') }}"
                            class="input-modern" style="width: 100%;">
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="button" @click="contributionOpen = false" class="btn"
                            style="flex: 1; justify-content: center; background: rgba(255,255,255,0.05); border-radius: 12px;">Annuler</button>
                        <button type="submit" class="btn btn-accent"
                            style="flex: 1; justify-content: center; border-radius: 12px; background: #10b981;">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .action-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-card:active {
            transform: scale(0.95);
        }

        .input-modern {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 14px;
            color: white;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-modern:focus {
            border-color: #10b981;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .slide-up {
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }
    </style>
@endsection