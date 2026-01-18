@extends('layouts.mobile')

@section('content')
    <div class="fade-in" x-data="{ 
                                    menuOpen: false, 
                                    activeDebt: null, 
                                    repaymentOpen: false,
                                    historyOpen: false,
                                    openMenu(debt) {
                                        this.activeDebt = debt;
                                        this.menuOpen = true;
                                        this.historyOpen = false;
                                    },
                                    closeMenu() {
                                        this.menuOpen = false;
                                        this.repaymentOpen = false;
                                    }
                                }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">{{ __('Mes Dettes') }}</h2>
            <a href="{{ route('debts.create') }}" class="btn btn-accent"
                style="padding: 10px 18px; border-radius: 14px; font-size: 13px;">+
                {{ __('Nouvelle') }}</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($debts as $debt)
                <div class="glass-card" @click="openMenu({{ Js::from($debt) }})"
                    style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: transform 0.2s;"
                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div
                            style="width: 45px; height: 45px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1" />
                                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-bold" style="font-size: 15px;">{{ $debt->creditor }}</p>
                            <p class="text-muted" style="font-size: 11px;">
                                {{ $debt->due_date ? __('Échéance:') . ' ' . \Carbon\Carbon::parse($debt->due_date)->translatedFormat('d M Y') : __("Pas d'échéance") }}
                            </p>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <p class="text-bold" style="color: #a855f7; font-size: 15px;">
                            {{ number_format($debt->remaining, 0, ',', ' ') }}
                            {{ auth()->user()->currency }}
                        </p>
                        <p class="text-muted" style="font-size: 9px; margin-top: -2px;">{{ __('Reste à payer') }}</p>
                        <span
                            style="font-size: 10px; background: {{ $debt->status == 'paid' ? 'rgba(16, 185, 129, 0.15)' : ($debt->status == 'late' ? 'rgba(239, 68, 68, 0.15)' : 'rgba(245, 158, 11, 0.15)') }}; color: {{ $debt->status == 'paid' ? '#10b981' : ($debt->status == 'late' ? '#ef4444' : '#f59e0b') }}; padding: 1px 6px; border-radius: 6px; font-weight: 600;">
                            {{ $debt->status == 'paid' ? __('Payée') : ($debt->status == 'late' ? __('En retard') : __('En attente')) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                    <p class="text-muted">{{ __('Aucune dette enregistrée.') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen" class="overlay" @click="closeMenu()" x-transition:enter="fade-in"
            x-transition:leave="fade-out" style="display: none;"></div>

        <div x-show="menuOpen" class="bottom-sheet" x-transition:enter="slide-up" x-transition:leave="slide-down"
            style="display: none; background: rgba(23, 23, 23, 0.98); backdrop-filter: blur(25px); border-top-left-radius: 35px; border-top-right-radius: 35px; padding: 25px 20px 120px; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1000; border-top: 1px solid rgba(255,255,255,0.12); height: auto; max-height: 85vh; overflow-y: auto;">
            <template x-if="activeDebt">
                <div>
                    <div
                        style="width: 40px; height: 5px; background: rgba(255,255,255,0.25); border-radius: 3px; margin: 0 auto 20px;">
                    </div>

                    <div style="text-align: center; margin-bottom: 25px;">
                        <h3 class="text-bold" style="font-size: 22px; margin-bottom: 5px;" x-text="activeDebt.creditor">
                        </h3>
                        <p style="color: #a855f7; font-weight: 700; font-size: 18px;"
                            x-text="new Intl.NumberFormat().format(activeDebt.amount) + ' {{ auth()->user()->currency }}'">
                        </p>
                        <template x-if="activeDebt.total_paid > 0">
                            <p class="text-muted" style="font-size: 13px; margin-top: 5px;">
                                {{ __('Reste à payer:') }} <span style="color: #ef4444; font-weight: 600;"
                                    x-text="new Intl.NumberFormat().format(activeDebt.remaining) + ' {{ auth()->user()->currency }}'"></span>
                            </p>
                        </template>
                    </div>

                    <!-- Status Selector -->
                    <div style="margin-bottom: 30px;">
                        <div style="display: flex; gap: 8px;">
                            <form :action="'/debts/' + activeDebt.id + '/status'" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit"
                                    :class="activeDebt.status == 'pending' ? 'status-pill-active active-pending' : 'status-pill'"
                                    style="width: 100%;">{{ __('En attente') }}</button>
                            </form>
                            <form :action="'/debts/' + activeDebt.id + '/status'" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="status" value="paid">
                                <button type="submit"
                                    :class="activeDebt.status == 'paid' ? 'status-pill-active active-paid' : 'status-pill'"
                                    style="width: 100%;">{{ __('Payée') }}</button>
                            </form>
                            <form :action="'/debts/' + activeDebt.id + '/status'" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="status" value="late">
                                <button type="submit"
                                    :class="activeDebt.status == 'late' ? 'status-pill-active active-late' : 'status-pill'"
                                    style="width: 100%;">{{ __('Retard') }}</button>
                            </form>
                        </div>
                    </div>

                    <!-- Main Actions Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px;">
                        <button @click="repaymentOpen = true" class="action-card"
                            style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.2); grid-column: span 2;">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">💸</span>
                            <span style="font-weight: 600; color: #a855f7;">{{ __('Rembourser') }}</span>
                        </button>

                        <button @click="historyOpen = !historyOpen" class="action-card"
                            :style="historyOpen ? 'background: rgba(255,255,255,0.15);' : ''">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">📜</span>
                            <span style="font-weight: 600;">{{ __('Historique') }}</span>
                        </button>

                        <a :href="'/debts/' + activeDebt.id + '/edit'" class="action-card"
                            style="text-decoration: none; color: white;">
                            <span style="font-size: 20px; margin-bottom: 5px; display: block;">✏️</span>
                            <span style="font-weight: 600;">{{ __('Modifier') }}</span>
                        </a>
                    </div>

                    <!-- History Section -->
                    <div x-show="historyOpen" x-transition:enter="fade-in"
                        style="background: rgba(255,255,255,0.03); border-radius: 20px; padding: 20px; margin-top: 10px;">
                        <h4 class="text-bold" style="font-size: 15px; margin-bottom: 15px;">
                            {{ __('Historique des paiements') }}</h4>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <template x-if="activeDebt.payments && activeDebt.payments.length > 0">
                                <template x-for="payment in activeDebt.payments" :key="payment.id">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <div>
                                            <p class="text-bold" style="font-size: 14px;"
                                                x-text="new Intl.NumberFormat().format(payment.amount) + ' {{ auth()->user()->currency }}'">
                                            </p>
                                            <p class="text-muted" style="font-size: 11px;"
                                                x-text="new Date(payment.payment_date).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'})">
                                            </p>
                                        </div>
                                        <span
                                            style="font-size: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 2px 8px; border-radius: 20px;">{{ __('Effectué') }}</span>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!activeDebt.payments || activeDebt.payments.length === 0">
                                <div style="text-align: center; padding: 20px;">
                                    <p class="text-muted" style="font-size: 13px;">{{ __('Aucun paiement trouvé') }}</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Repayment Modal -->
        <div x-show="repaymentOpen" class="overlay" style="z-index: 1001; background: rgba(0,0,0,0.85); display: none;"
            x-transition:enter="fade-in" x-transition:leave="fade-out">
            <div class="glass-card" @click.stop
                style="max-width: 90%; width: 350px; margin: auto; padding: 30px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.15);">
                <h3 class="text-bold" style="margin-bottom: 5px; font-size: 18px;">{{ __('Enregistrer un paiement') }}</h3>
                <p class="text-muted" style="font-size: 12px; margin-bottom: 25px;">
                    {{ __('Ajouter un remboursement pour') }} <span x-text="activeDebt?.creditor"
                        style="color: white; font-weight: 600;"></span></p>

                <form action="{{ route('debts.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="debt_id" :value="activeDebt?.id">

                    <div style="margin-bottom: 18px;">
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">{{ __('Montant à payer') }}</label>
                        <input type="number" name="amount" required step="0.01" class="input-modern"
                            style="width: 100%; font-size: 16px; font-weight: 600;"
                            placeholder="0 {{ auth()->user()->currency }}">
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label class="text-muted"
                            style="font-size: 11px; display: block; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">{{ __('Date du paiement') }}</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="input-modern"
                            style="width: 100%;">
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="button" @click="repaymentOpen = false" class="btn"
                            style="flex: 1; justify-content: center; background: rgba(255,255,255,0.05); border-radius: 12px;">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-accent"
                            style="flex: 1; justify-content: center; border-radius: 12px;">{{ __('Confirmer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .status-pill {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            padding: 12px 5px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .status-pill-active {
            padding: 12px 5px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: 2px solid white;
        }

        .active-pending {
            background: #f59e0b;
            color: white;
        }

        .active-paid {
            background: #10b981;
            color: white;
        }

        .active-late {
            background: #ef4444;
            color: white;
        }

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
            border-color: #a855f7;
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