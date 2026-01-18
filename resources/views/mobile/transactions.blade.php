@extends('layouts.mobile')

@section('content')
    <div class="fade-in" x-data="{
                    menuOpen: false,
                    activeTransaction: null,
                    openMenu(transaction) {
                        this.activeTransaction = transaction;
                        this.menuOpen = true;
                    },
                    closeMenu() {
                        this.menuOpen = false;
                    }
                }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">{{ __('Transactions') }}</h2>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('revenues.create') }}" class="btn btn-accent"
                    style="padding: 8px 12px; font-size: 12px;">+ {{ __('Revenu') }}</a>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary"
                    style="padding: 8px 12px; font-size: 12px;">+ {{ __('Dépense') }}</a>
            </div>
        </div>

        @php
            $transactions = collect()
                ->concat($revenues->map(function ($r) {
                    $r->type = 'revenue';
                    $r->tx_date = $r->due_date;
                    return $r;
                }))
                ->concat($expenses->map(function ($e) {
                    $e->type = 'expense';
                    $e->tx_date = $e->date;
                    return $e;
                }))
                ->sortByDesc('tx_date');
        @endphp

        @if($transactions->isEmpty())
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">{{ __('Aucune transaction trouvée.') }}</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($transactions as $item)
                    <div class="glass-card" @click="openMenu({{ Js::from($item) }})"
                        style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; cursor: pointer; transition: transform 0.2s;"
                        onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div
                                style="width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: {{ $item->category->color ?? ($item->type == 'revenue' ? 'var(--accent-blue)' : '#ef4444') }}20; color: {{ $item->category->color ?? ($item->type == 'revenue' ? 'var(--accent-blue)' : '#ef4444') }}; font-size: 20px;">
                                {{ $item->category->icon ?? ($item->type == 'revenue' ? '📈' : '📉') }}
                            </div>
                            <div>
                                <p class="text-bold" style="font-size: 14px;">
                                    {{ $item->type == 'revenue' ? $item->source : ($item->category->name ?? $item->category) }}
                                </p>
                                <p class="text-muted" style="font-size: 11px;">
                                    {{ $item->category->name ?? ($item->type == 'revenue' ? __('Revenu') : __('Dépense')) }} •
                                    {{ \Carbon\Carbon::parse($item->tx_date)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <p class="text-bold" style="color: {{ $item->type == 'revenue' ? 'var(--primary-green)' : '#ef4444' }};">
                            {{ $item->type == 'revenue' ? '+' : '-' }}{{ number_format($item->amount, 0, ',', ' ') }}
                            {{ auth()->user()->currency }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen" class="overlay" @click="closeMenu()" x-transition:enter="fade-in"
            x-transition:leave="fade-out" style="display: none;"></div>

        <div x-show="menuOpen" class="bottom-sheet" x-transition:enter="slide-up" x-transition:leave="slide-down"
            style="display: none; background: var(--bg-color); border: 1px solid var(--card-border); backdrop-filter: blur(25px); border-top-left-radius: 35px; border-top-right-radius: 35px; padding: 25px 20px 80px; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000;">
            <template x-if="activeTransaction">
                <div>
                    <div
                        style="width: 40px; height: 5px; background: var(--text-muted); opacity: 0.3; border-radius: 3px; margin: 0 auto 20px;">
                    </div>

                    <div style="text-align: center; margin-bottom: 25px;">
                        <div
                            style="width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                            <span
                                x-text="activeTransaction.category?.icon || (activeTransaction.type == 'revenue' ? '📈' : '📉')"></span>
                        </div>
                        <h3 class="text-bold" style="font-size: 22px; margin-bottom: 5px;"
                            x-text="activeTransaction.type == 'revenue' ? activeTransaction.source : (activeTransaction.category?.name || activeTransaction.category)">
                        </h3>

                        <p x-show="activeTransaction.type == 'revenue'"
                            style="color: #10b981; font-weight: 700; font-size: 18px;"
                            x-text="'+ ' + new Intl.NumberFormat().format(activeTransaction.amount) + ' {{ auth()->user()->currency }}'">
                        </p>
                        <p x-show="activeTransaction.type == 'expense'"
                            style="color: #ef4444; font-weight: 700; font-size: 18px;"
                            x-text="'- ' + new Intl.NumberFormat().format(activeTransaction.amount) + ' {{ auth()->user()->currency }}'">
                        </p>

                        <p class="text-muted" style="font-size: 13px; margin-top: 5px;">
                            <span
                                x-text="activeTransaction.category?.name || (activeTransaction.type == 'revenue' ? '{{ __('Revenu') }}' : '{{ __('Dépense') }}')"></span>
                            •
                            <span
                                x-text="new Date(activeTransaction.tx_date).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'})"></span>
                        </p>
                    </div>

                    <!-- Actions Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                        <a :href="(activeTransaction.type == 'revenue' ? '/revenues/' : '/expenses/') + activeTransaction.id + '/edit'"
                            class="action-card" style="text-decoration: none; color: var(--text-main);">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">✏️</span>
                            <span style="font-weight: 600;">{{ __('Modifier') }}</span>
                        </a>

                        <form
                            :action="(activeTransaction.type == 'revenue' ? '/revenues/' : '/expenses/') + activeTransaction.id"
                            method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr ?') }}');" class="action-card"
                            style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #ef4444; margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background: none; border: none; color: inherit; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer;">
                                <span style="font-size: 20px; margin-bottom: 5px; display: block;">🗑️</span>
                                <span style="font-weight: 600;">{{ __('Supprimer') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>

        <style>
            .action-card {
                background: var(--card-bg);
                border: 1px solid var(--card-border);
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