@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="glass-card"
                style="background: rgba(34, 197, 94, 0.1); border-left: 4px solid #22c55e; margin-bottom: 15px;">
                <p style="color: #22c55e; margin: 0;">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="glass-card"
                style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; margin-bottom: 15px;">
                <p style="color: #ef4444; margin: 0;">✗ {{ session('error') }}</p>
            </div>
        @endif

        @if ($message)
            <div class="glass-card"
                style="background: rgba({{ $message['type'] === 'success' ? '34, 197, 94' : '251, 146, 60' }}, 0.1); border-left: 4px solid {{ $message['type'] === 'success' ? '#22c55e' : '#fb923c' }}; margin-bottom: 15px;">
                <p style="color: {{ $message['type'] === 'success' ? '#22c55e' : '#fb923c' }}; margin: 0;">
                    {{ $message['text'] }}</p>
            </div>
        @endif

        {{-- Naboopay Balance Card --}}
        <div class="glass-card"
            style="background: linear-gradient(135deg, var(--accent-blue) 0%, #2563eb 100%); border: none; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="opacity: 0.8; font-size: 14px;">💳 Solde Naboopay</p>
                    <h2 class="text-bold" style="font-size: 32px; margin-top: 5px;">
                        {{ number_format($user->naboopay_balance, 0, ',', ' ') }} XOF
                    </h2>
                    <p style="opacity: 0.7; font-size: 12px; margin-top: 5px;">Argent réel disponible</p>
                </div>
                <div
                    style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; font-size: 28px;">
                    💰
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
            <button onclick="document.getElementById('checkoutModal').style.display='flex'" class="glass-card"
                style="border: 2px solid var(--accent-blue); cursor: pointer; text-align: center; padding: 20px;">
                <div
                    style="width: 50px; height: 50px; border-radius: 14px; background: rgba(var(--accent-rgb), 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; color: var(--accent-blue); font-size: 24px;">
                    📥
                </div>
                <p class="text-bold" style="color: var(--accent-blue); margin: 0;">Déposer</p>
                <p class="text-muted" style="font-size: 11px; margin-top: 5px;">Ajouter de l'argent</p>
            </button>

            <button onclick="document.getElementById('payoutModal').style.display='flex'" class="glass-card"
                style="border: 2px solid #22c55e; cursor: pointer; text-align: center; padding: 20px;">
                <div
                    style="width: 50px; height: 50px; border-radius: 14px; background: rgba(34, 197, 94, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; color: #22c55e; font-size: 24px;">
                    📤
                </div>
                <p class="text-bold" style="color: #22c55e; margin: 0;">Retirer</p>
                <p class="text-muted" style="font-size: 11px; margin-top: 5px;">Vers mobile money</p>
            </button>
        </div>

        {{-- Transaction History --}}
        <div class="glass-card" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <p class="text-bold">📊 Historique des transactions</p>
            </div>

            @if ($transactions->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach ($transactions as $transaction)
                        <div
                            style="padding: 12px; border-radius: 12px; background: var(--card-bg); border: 1px solid var(--card-border);">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <span style="font-size: 20px;">{{ $transaction->type === 'checkout' ? '📥' : '📤' }}</span>
                                        <span class="text-bold" style="font-size: 14px;">
                                            {{ $transaction->type === 'checkout' ? 'Dépôt' : 'Retrait' }}
                                        </span>
                                        <span
                                            class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}"
                                            style="font-size: 10px; padding: 2px 8px; border-radius: 10px;">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>
                                    <p class="text-muted" style="font-size: 11px; margin: 0;">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        @if ($transaction->payment_method)
                                            • {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                        @endif
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <p class="text-bold"
                                        style="color: {{ $transaction->type === 'checkout' ? 'var(--accent-blue)' : '#ef4444' }}; margin: 0;">
                                        {{ $transaction->type === 'checkout' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', ' ') }}
                                        XOF
                                    </p>
                                </div>
                            </div>
                            @if ($transaction->error_message)
                                <p style="color: #ef4444; font-size: 11px; margin-top: 8px;">⚠️ {{ $transaction->error_message }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 15px;">
                    {{ $transactions->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 30px 0;">
                    <p style="font-size: 40px; margin-bottom: 10px;">💸</p>
                    <p class="text-muted">Aucune transaction pour le moment</p>
                    <p class="text-muted" style="font-size: 12px;">Commencez par déposer de l'argent</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Checkout Modal --}}
    <div id="checkoutModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div class="glass-card" style="max-width: 400px; width: 100%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="text-bold" style="margin: 0;">📥 Déposer de l'argent</h3>
                <button onclick="document.getElementById('checkoutModal').style.display='none'"
                    style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted);">×</button>
            </div>

            <form action="{{ route('naboopay.checkout') }}" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="display: block; margin-bottom: 8px; font-size: 13px;">Montant
                        (XOF)</label>
                    <input type="number" name="amount" min="100" step="1" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); font-size: 16px;"
                        placeholder="Ex: 5000">
                    <p class="text-muted" style="font-size: 11px; margin-top: 5px;">Minimum: 100 XOF</p>
                </div>

                <div
                    style="background: rgba(var(--accent-rgb), 0.1); padding: 12px; border-radius: 12px; margin-bottom: 20px;">
                    <p class="text-muted" style="font-size: 12px; margin: 0;">
                        💳 Vous serez redirigé vers Naboopay pour effectuer le paiement via Wave, Orange Money ou Carte
                        bancaire.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 14px; border-radius: 12px; font-weight: 600;">
                    Continuer vers le paiement
                </button>
            </form>
        </div>
    </div>

    {{-- Payout Modal --}}
    <div id="payoutModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div class="glass-card" style="max-width: 400px; width: 100%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="text-bold" style="margin: 0;">📤 Retirer de l'argent</h3>
                <button onclick="document.getElementById('payoutModal').style.display='none'"
                    style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted);">×</button>
            </div>

            <div style="background: rgba(var(--accent-rgb), 0.1); padding: 12px; border-radius: 12px; margin-bottom: 20px;">
                <p class="text-muted" style="font-size: 12px; margin: 0;">
                    💰 Solde disponible: <span class="text-bold">{{ number_format($user->naboopay_balance, 0, ',', ' ') }}
                        XOF</span>
                </p>
            </div>

            <form action="{{ route('naboopay.payout') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label class="text-muted" style="display: block; margin-bottom: 8px; font-size: 13px;">Montant
                        (XOF)</label>
                    <input type="number" name="amount" min="10" max="{{ $user->naboopay_balance }}" step="1" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); font-size: 16px;"
                        placeholder="Ex: 2000">
                    <p class="text-muted" style="font-size: 11px; margin-top: 5px;">Minimum: 10 XOF</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <label class="text-muted" style="display: block; margin-bottom: 8px; font-size: 13px;">Numéro de
                        téléphone</label>
                    <input type="tel" name="phone_number" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); font-size: 16px;"
                        placeholder="Ex: 771234567">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="text-muted" style="display: block; margin-bottom: 8px; font-size: 13px;">Opérateur</label>
                    <select name="provider" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--card-border); background: var(--card-bg); color: var(--text-primary); font-size: 16px;">
                        <option value="">Sélectionner un opérateur</option>
                        <option value="wave">Wave</option>
                        <option value="orange_money">Orange Money</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 14px; border-radius: 12px; font-weight: 600; background: #22c55e; border-color: #22c55e;">
                    Retirer l'argent
                </button>
            </form>
        </div>
    </div>

    <style>
        .badge-success {
            background: #22c55e;
            color: white;
        }

        .badge-warning {
            background: #fb923c;
            color: white;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
        }
    </style>
@endsection