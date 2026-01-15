@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 25px;">Dashboard Global</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Users</p>
                <h3 class="text-bold" style="font-size: 24px; color: var(--accent-blue);">{{ $userCount }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Revenus</p>
                <h3 class="text-bold" style="font-size: 20px; color: var(--primary-green);">
                    {{ number_format($totalGlobalRevenue, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Dettes</p>
                <h3 class="text-bold" style="font-size: 20px; color: #ef4444;">
                    {{ number_format($totalGlobalDebts, 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card" style="padding: 20px;">
                <p class="text-muted"
                    style="font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Moy. Dette
                </p>
                <h3 class="text-bold" style="font-size: 20px; color: white;">
                    {{ number_format($avgDebtPerUser, 0, ',', ' ') }}</h3>
            </div>
        </div>

        <div class="glass-card" style="padding: 20px; margin-bottom: 30px;">
            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 20px;">Utilisateurs Récents</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach (App\Models\User::latest()->take(3)->get() as $user)
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <p class="text-bold" style="font-size: 14px;">{{ $user->name }}</p>
                            <p class="text-muted" style="font-size: 11px;">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                            style="color: var(--accent-blue); font-size: 12px; font-weight: 600; text-decoration: none;">Voir</a>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary"
                style="width: 100%; margin-top: 20px; text-align: center; border-radius: 12px; font-size: 14px;">Gérer tous
                les utilisateurs</a>
        </div>
    </div>
@endsection