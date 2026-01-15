<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Saving;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalRevenue = Revenue::where('user_id', '=', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', '=', $user->id)->sum('amount');
        $totalDebts = Debt::where('user_id', '=', $user->id)->where('status', '=', 'pending')->sum('amount');
        $totalSavings = Saving::where('user_id', '=', $user->id)->sum('current_amount');

        $balance = $totalRevenue - $totalExpenses;

        return view('mobile.dashboard', compact('balance', 'totalDebts', 'totalSavings', 'totalRevenue', 'totalExpenses'));
    }

    public function settings()
    {
        $settings = Auth::user()->settings ?? new UserSetting();
        return view('mobile.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'currency' => 'required|string',
            'language' => 'required|string',
            'notifications_enabled' => 'nullable|boolean',
            'theme' => 'required|string|in:dark,light',
        ]);

        $settings = Auth::user()->settings;

        if (!$settings) {
            $settings = new UserSetting();
            $settings->user_id = Auth::id();
        }

        $settings->currency = $validated['currency'];
        $settings->language = $validated['language'];
        $settings->theme = $validated['theme'];
        $settings->notifications_enabled = $request->has('notifications_enabled');
        $settings->save();

        return back()->with('success', 'Paramètres mis à jour !');
    }
}
