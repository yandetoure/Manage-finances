@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="text-bold">Utilisateurs</h2>
            <div
                style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 12px; font-size: 12px; color: var(--accent-blue); font-weight: 600;">
                {{ $users->count() }} Total
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 80px;">
            @foreach($users as $user)
                <div class="glass-card" style="padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 12px;">
                        <div
                            style="width: 45px; height: 45px; border-radius: 50%; background: var(--accent-blue); display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div style="flex: 1;">
                            <p class="text-bold" style="font-size: 15px;">{{ $user->name }}</p>
                            <p class="text-muted" style="font-size: 11px;">{{ $user->email }}</p>
                        </div>
                        <span
                            style="background: rgba(59, 130, 246, 0.15); color: var(--accent-blue); padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase;">
                            {{ $user->getRoleNames()->first() ?? 'User' }}
                        </span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.05);">
                        <span class="text-muted" style="font-size: 11px;">Inscrit le
                            {{ $user->created_at->format('d/m/Y') }}</span>
                        <div style="display: flex; gap: 15px;">
                            <a href="{{ route('admin.modules.index') }}?user_id={{ $user->id }}"
                                style="color: var(--primary-green); font-size: 12px; font-weight: 600; text-decoration: none;">Modules</a>
                            {{-- <a href="#"
                                style="color: var(--accent-blue); font-size: 12px; font-weight: 600; text-decoration: none;">Gérer</a>
                            --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection