<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $settings = $user->settings;

        // Return flattened structure matching Flutter UserSettings model
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'currency' => $settings?->currency ?? 'FCFA',
            'language' => $settings?->language ?? 'fr',
            'theme' => $settings?->theme ?? 'dark',
            'notifications_enabled' => $settings?->notifications_enabled ?? true,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'currency' => 'required|string',
            'language' => 'required|string',
            'theme' => 'required|string|in:dark,light',
            'notifications_enabled' => 'boolean',
        ]);

        // Update User Profile
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // Update User Settings
        $settings = $user->settings;

        if (!$settings) {
            $settings = new UserSetting();
            $settings->user_id = $user->id;
        }

        $settings->currency = $validated['currency'];
        $settings->language = $validated['language'];
        $settings->theme = $validated['theme'];
        $settings->notifications_enabled = $validated['notifications_enabled'] ?? false;
        $settings->save();

        // Return flattened structure matching Flutter UserSettings model
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'currency' => $settings->currency,
            'language' => $settings->language,
            'theme' => $settings->theme,
            'notifications_enabled' => $settings->notifications_enabled,
        ]);
    }
}
