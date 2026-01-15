<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('user');

        // Initialize default modules
        $modules = ['revenues', 'expenses', 'debts', 'claims', 'savings', 'forecasts'];
        foreach ($modules as $module) {
            ModuleSetting::create([
                'user_id' => $user->id,
                'module_name' => $module,
                'is_enabled' => true,
            ]);
        }

        Auth::login($user);

        return redirect()->route('home');
    }
}
