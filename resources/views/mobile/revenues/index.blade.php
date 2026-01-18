@extends('layouts.mobile')

@section('content')
    <div class="fade-in" x-data="{
                menuOpen: false,
                activeRevenue: null,
                openMenu(revenue) {
                    this.activeRevenue = revenue;
                    this.menuOpen = true;
                },
                closeMenu() {
                    this.menuOpen = false;
                }
            }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="text-bold">Mes Revenus</h2>
            <a href="{{ route('revenues.create') }}" class="btn btn-primary"
                style="padding: 8px 16px; border-radius: 50px;">+ Nouveau</a>
        </div>

        @forelse($revenues as $revenue)
            <div class="glass-card fade-in" @click="openMenu({{ Js::from($revenue) }})"
                style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: transform 0.2s;"
                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="width: 45px; height: 45px; background: {{ $revenue->category->color ?? 'rgba(255,255,255,0.1)' }}20; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        {{ $revenue->category->icon ?? '➕' }}
                    </div>
                    <div>
                        <p class="text-bold">{{ $revenue->source }}</p>
                        <p class="text-muted" style="font-size: 11px;">
                            {{ $revenue->category->name ?? 'Sans catégorie' }} • {{ $revenue->due_date ?? 'Pas de date' }}
                        </p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <p class="text-bold text-green">+ {{ number_format($revenue->amount, 0, ',', ' ') }}
                        {{ auth()->user()->currency }}
                    </p>
                    @if($revenue->is_recurrent)
                        <span
                            style="font-size: 10px; background: rgba(59, 130, 246, 0.2); color: var(--accent-blue); padding: 2px 6px; border-radius: 10px;">Récurrent</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucun revenu enregistré.</p>
            </div>
        @endforelse

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen" class="overlay" @click="closeMenu()" x-transition:enter="fade-in"
            x-transition:leave="fade-out" style="display: none;"></div>

        <div x-show="menuOpen" class="bottom-sheet" x-transition:enter="slide-up" x-transition:leave="slide-down"
            style="display: none; background: rgba(23, 23, 23, 0.98); backdrop-filter: blur(25px); border-top-left-radius: 35px; border-top-right-radius: 35px; padding: 25px 20px 80px; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; border-top: 1px solid rgba(255,255,255,0.12);">
            <template x-if="activeRevenue">
                <div>
                    <div
                        style="width: 40px; height: 5px; background: rgba(255,255,255,0.25); border-radius: 3px; margin: 0 auto 20px;">
                    </div>

                    <div style="text-align: center; margin-bottom: 25px;">
                        <div
                            style="width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                            <span x-text="activeRevenue.category?.icon || '➕'"></span>
                        </div>
                        <h3 class="text-bold" style="font-size: 22px; margin-bottom: 5px;" x-text="activeRevenue.source">
                        </h3>
                        <p style="color: #10b981; font-weight: 700; font-size: 18px;"
                            x-text="new Intl.NumberFormat().format(activeRevenue.amount) + ' {{ auth()->user()->currency }}'">
                        </p>
                        <p class="text-muted" style="font-size: 13px; margin-top: 5px;">
                            <span x-text="activeRevenue.category?.name || 'Sans catégorie'"></span> • <span
                                x-text="activeRevenue.due_date"></span>
                        </p>
                    </div>

                    <!-- Actions Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                        <a :href="'/revenues/' + activeRevenue.id + '/edit'" class="action-card"
                            style="text-decoration: none; color: white;">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">✏️</span>
                            <span style="font-weight: 600;">Modifier</span>
                        </a>

                        <form :action="'/revenues/' + activeRevenue.id" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr ?');" class="action-card"
                            style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #ef4444; margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background: none; border: none; color: inherit; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer;">
                                <span style="font-size: 20px; margin-bottom: 5px; display: block;">🗑️</span>
                                <span style="font-weight: 600;">Supprimer</span>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
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

            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(8px);
                z-index: 999;
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
    </div>
@endsection