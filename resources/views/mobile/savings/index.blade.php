@extends('layouts.mobile')

@section('content')
    <div x-data="{ 
                menuOpen: false, 
                activeSaving: null,
                contributionOpen: false,
                openMenu(savingId) {
                    this.activeSaving = savingId;
                    this.menuOpen = true;
                }
            }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Épargne</h2>
            <a href="{{ route('savings.create') }}" class="btn btn-accent" style="padding: 8px 16px; border-radius: 50px;">+
                Nouveau Projet</a>
        </div>

        @if($savings->isEmpty())
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucun projet d'épargne en cours.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($savings as $saving)
                    @php
                        $percentage = min(100, ($saving->current_amount / $saving->target_amount) * 100);
                    @endphp
                    <div class="glass-card" style="padding: 20px; cursor: pointer;" @click="openMenu({{ $saving->id }})">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span class="text-bold">{{ $saving->target_name }}</span>
                            <span class="text-muted" style="font-size: 12px;">{{ number_format($percentage, 0) }}%</span>
                        </div>
                        <div
                            style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; margin-bottom: 10px;">
                            <div
                                style="width: {{ $percentage }}%; height: 100%; background: linear-gradient(to right, var(--accent-blue), var(--primary-green));">
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <span
                                style="font-size: 14px; color: var(--primary-green); font-weight: bold;">{{ number_format($saving->current_amount, 0, ',', ' ') }}
                                FCFA</span>
                            <span style="font-size: 11px; color: var(--text-muted);">Objectif :
                                {{ number_format($saving->target_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            style="position: fixed; bottom: 0; left: 0; right: 0; background: #0f172a; border-top: 1px solid rgba(255,255,255,0.1); border-radius: 24px 24px 0 0; padding: 25px 25px 120px; z-index: 1000; box-shadow: 0 -10px 25px rgba(0,0,0,0.5);">

            <div
                style="width: 40px; height: 5px; background: rgba(255,255,255,0.2); border-radius: 3px; margin: 0 auto 20px;">
            </div>

            <template x-if="activeSaving">
                <div>
                    <h3 class="text-bold" style="margin-bottom: 20px;">Actions</h3>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button @click="contributionOpen = true; menuOpen = false" class="btn btn-accent"
                            style="width: 100%; background: var(--primary-green);">💰 Ajouter une épargne</button>

                        <!-- History Section Toggle -->
                        <div x-data="{ historyOpen: false }">
                            <button @click="historyOpen = !historyOpen" class="btn btn-secondary-outline"
                                style="width: 100%; text-align: left; padding: 12px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                                <span>📜 Historique des économies</span>
                                <span x-text="historyOpen ? '▲' : '▼'"></span>
                            </button>

                            <div x-show="historyOpen"
                                style="margin-top: 10px; padding: 10px; background: rgba(0,0,0,0.2); border-radius: 12px; max-height: 200px; overflow-y: auto;">
                                <template x-for="saving in {{ $savings->toJson() }}">
                                    <template x-if="saving.id == activeSaving">
                                        <div>
                                            <template x-if="saving.contributions.length === 0">
                                                <p class="text-muted" style="font-size: 11px; text-align: center;">Aucune
                                                    économie.</p>
                                            </template>
                                            <template x-for="contribution in saving.contributions">
                                                <div
                                                    style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.05); padding: 8px 0;">
                                                    <span style="font-size: 11px; color: var(--text-muted);"
                                                        x-text="contribution.contribution_date"></span>
                                                    <span
                                                        style="font-size: 11px; font-weight: bold; color: var(--primary-green);"
                                                        x-text="'+' + new Intl.NumberFormat().format(contribution.amount) + ' FCFA'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>

                        <a :href="'/savings/' + activeSaving + '/edit'" class="btn btn-secondary-outline"
                            style="width: 100%; text-align: center; text-decoration: none; display: block; padding: 12px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #fff;">✏️
                            Modifier le projet</a>
                        <button @click="menuOpen = false" class="btn" style="width: 100%; border: none;">Annuler</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Contribution Modal -->
        <div x-show="contributionOpen" class="fade-in"
            style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 1001; display: flex; align-items: center; justify-content: center; padding: 20px;">
            <div class="glass-card" style="max-width: 400px; width: 100%;">
                <h3 class="text-bold" style="margin-bottom: 20px;">Épargner pour ce projet</h3>
                <form action="{{ route('savings.contribute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="saving_id" :value="activeSaving">
                    <div style="margin-bottom: 15px;">
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 5px;">Montant à
                            ajouter</label>
                        <input type="number" name="amount" required class="form-input"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 12px; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 5px;">Date</label>
                        <input type="date" name="contribution_date" required value="{{ date('Y-m-d') }}" class="form-input"
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 12px; border-radius: 8px;">
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" @click="contributionOpen = false" class="btn"
                            style="flex: 1; background: rgba(255,255,255,0.1);">Annuler</button>
                        <button type="submit" class="btn btn-accent"
                            style="flex: 2; background: var(--primary-green);">Valider</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Overlay -->
        <div x-show="menuOpen" @click="menuOpen = false"
            style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999;"></div>
    </div>
@endsection