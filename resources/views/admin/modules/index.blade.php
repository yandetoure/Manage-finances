@extends('layouts.admin')

@section('content')
    <div class="fade-in">
        <h2 class="text-bold" style="margin-bottom: 30px;">Gestion des Modules SaaS</h2>

        @foreach($users as $user)
            <div class="stat-card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 class="text-bold">{{ $user->name }} <span class="text-muted"
                            style="font-weight: normal; font-size: 14px;">({{ $user->email }})</span></h3>
                    <span
                        style="background: rgba(59, 130, 246, 0.2); color: var(--accent-blue); padding: 4px 12px; border-radius: 20px; font-size: 12px;">Plan:
                        {{ $user->hasRole('admin') ? 'Administrateur' : 'Standard' }}</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                    @php
                        $modules = ['revenues' => 'Revenus', 'expenses' => 'Dépenses', 'debts' => 'Dettes', 'claims' => 'Créances', 'savings' => 'Épargne', 'forecasts' => 'Prévisions'];
                    @endphp

                    @foreach($modules as $key => $label)
                        @php
                            $setting = $user->moduleSettings->where('module_name', $key)->first();
                            $isEnabled = $setting ? $setting->is_enabled : true;
                        @endphp
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.03); padding: 12px; border-radius: 12px;">
                            <span style="font-size: 14px;">{{ $label }}</span>
                            <input type="checkbox" {{ $isEnabled ? 'checked' : '' }}
                                onchange="toggleModule({{ $user->id }}, '{{ $key }}', this.checked)"
                                style="width: 20px; height: 20px; accent-color: var(--primary-green); cursor: pointer;">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

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
                        // Notification légère si besoin
                    }
                });
        }
    </script>
@endsection