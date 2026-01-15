@extends('layouts.mobile')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 25px;">Modules par Utilisateur</h2>

        @php
            $targetUserId = request('user_id');
            $displayUsers = $targetUserId ? $users->where('id', $targetUserId) : $users;
        @endphp

        @if($targetUserId)
            <div style="margin-bottom: 20px;">
                <a href="{{ route('admin.modules.index') }}"
                    style="color: var(--accent-blue); font-size: 13px; text-decoration: none; font-weight: 600;">← Voir tous les
                    utilisateurs</a>
            </div>
        @endif

        <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 80px;">
            @foreach($displayUsers as $user)
                <div class="glass-card" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div>
                            <h3 class="text-bold" style="font-size: 16px; margin-bottom: 4px;">{{ $user->name }}</h3>
                            <p class="text-muted" style="font-size: 11px;">{{ $user->email }}</p>
                        </div>
                        <span
                            style="background: rgba(16, 185, 129, 0.1); color: var(--primary-green); padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; text-transform: uppercase;">
                            {{ $user->hasRole('admin') ? 'Administrateur' : 'Standard' }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        @php
                            $modules = [
                                'revenues' => ['label' => 'Revenus', 'icon' => '📈'],
                                'expenses' => ['label' => 'Dépenses', 'icon' => '📉'],
                                'debts' => ['label' => 'Dettes', 'icon' => '💸'],
                                'claims' => ['label' => 'Créances', 'icon' => '⚖️'],
                                'savings' => ['label' => 'Épargne', 'icon' => '💰'],
                                'forecasts' => ['label' => 'Prévisions', 'icon' => '🔮']
                            ];
                        @endphp

                        @foreach($modules as $key => $data)
                            @php
                                $setting = $user->moduleSettings->where('module_name', $key)->first();
                                $isEnabled = $setting ? $setting->is_enabled : true;
                            @endphp
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 12px 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="font-size: 18px;">{{ $data['icon'] }}</span>
                                    <span style="font-size: 14px; font-weight: 500;">{{ $data['label'] }}</span>
                                </div>

                                <label class="switch">
                                    <input type="checkbox" {{ $isEnabled ? 'checked' : '' }}
                                        onchange="toggleModule({{ $user->id }}, '{{ $key }}', this.checked)">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* Premium Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            transition: .4s;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input:checked+.slider {
            background-color: var(--primary-green);
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <script>
        function toggleModule(userId, moduleName, isEnabled) {
            fetch("{{ route('admin.modules.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    user_id: userId,
                    module_name: moduleName,
                    is_enabled: isEnabled ? 1 : 0
                })
            }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Success micro-interaction could be added here
                    }
                });
        }
    </script>
@endsection