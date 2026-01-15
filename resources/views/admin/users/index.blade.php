@extends('layouts.admin')

@section('content')
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 class="text-bold">Gestion des Utilisateurs</h2>
            {{-- <a href="#" class="btn btn-primary">+ Nouvel Utilisateur</a> --}}
        </div>

        <div class="stat-card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th style="padding: 12px; color: var(--text-muted);">Nom</th>
                        <th style="padding: 12px; color: var(--text-muted);">Email</th>
                        <th style="padding: 12px; color: var(--text-muted);">Rôle</th>
                        <th style="padding: 12px; color: var(--text-muted);">Inscription</th>
                        <th style="padding: 12px; color: var(--text-muted);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 12px;">{{ $user->name }}</td>
                            <td style="padding: 12px;">{{ $user->email }}</td>
                            <td style="padding: 12px;">
                                <span
                                    style="background: rgba(59, 130, 246, 0.2); color: var(--accent-blue); padding: 2px 8px; border-radius: 10px; font-size: 12px;">
                                    {{ $user->getRoleNames()->first() ?? 'User' }}
                                </span>
                            </td>
                            <td style="padding: 12px;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 12px;">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue">Gérer</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection