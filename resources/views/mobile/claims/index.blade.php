@extends('layouts.mobile')

@section('content')
    <div x-data="{
            menuOpen: false,
            activeClaim: null,
            openMenu(claimId) {
                this.activeClaim = claimId;
                this.menuOpen = true;
            }
        }">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Créances</h2>
            <a href="{{ route('claims.create') }}" class="btn btn-accent" style="padding: 8px 16px; border-radius: 50px;">+
                Ajouter</a>
        </div>

        @if($claims->isEmpty())
            <div class="glass-card" style="text-align: center; padding: 40px 20px;">
                <p class="text-muted">Aucune créance enregistrée.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($claims as $claim)
                    <div class="glass-card"
                        style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; cursor: pointer;"
                        @click="openMenu({{ $claim->id }})">
                        <div>
                            <p class="text-bold" style="font-size: 14px;">{{ $claim->debtor }}</p>
                            <p class="text-muted" style="font-size: 12px;">Échéance :
                                {{ \Carbon\Carbon::parse($claim->due_date)->format('d M Y') }}</p>
                            <span
                                style="font-size: 10px; padding: 2px 8px; border-radius: 10px; background: {{ $claim->status == 'paid' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(255, 255, 255, 0.05)' }}; color: {{ $claim->status == 'paid' ? 'var(--primary-green)' : 'var(--text-muted)' }};">
                                {{ ucfirst($claim->status) }}
                            </span>
                        </div>
                        <p class="text-bold" style="color: var(--primary-green);">
                            +{{ number_format($claim->amount, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Action Menu (Bottom Sheet) -->
        <div x-show="menuOpen"
            style="position: fixed; bottom: 0; left: 0; right: 0; background: #0f172a; border-top: 1px solid rgba(255,255,255,0.1); border-radius: 24px 24px 0 0; padding: 25px; z-index: 1000;">

            <div
                style="width: 40px; height: 5px; background: rgba(255,255,255,0.2); border-radius: 3px; margin: 0 auto 20px;">
            </div>

            <template x-if="activeClaim">
                <div>
                    <h3 class="text-bold" style="margin-bottom: 20px;">Actions</h3>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <form :action="'/claims/' + activeClaim + '/toggle-paid'" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-accent" style="width: 100%;">✔️ Marquer comme payé/non
                                payé</button>
                        </form>
                        <a :href="'/claims/' + activeClaim + '/edit'" class="btn btn-secondary-outline"
                            style="width: 100%; text-align: center; text-decoration: none; display: block; padding: 12px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: #fff;">✏️
                            Modifier la créance</a>
                        <button @click="menuOpen = false" class="btn" style="width: 100%; border: none;">Annuler</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Overlay -->
        <div x-show="menuOpen" @click="menuOpen = false"
            style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999;"></div>
    </div>
@endsection