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

        $totalRevenue = Revenue::where('user_id', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $totalDebts = Debt::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        $totalSavings = Saving::where('user_id', $user->id)->sum('current_amount');

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
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'currency' => 'required|string',
            'language' => 'required|string',
            'notifications_enabled' => 'nullable|boolean',
            'theme' => 'required|string|in:dark,light',
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
        $settings->notifications_enabled = $request->has('notifications_enabled');
        $settings->save();

        return back()->with('success', 'Profil et paramètres mis à jour !');
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $selectedDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        $totalRevenue = Revenue::where('user_id', $user->id)
            ->where(function ($query) use ($month, $year, $selectedDate) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('due_date', $month)->whereYear('due_date', $year);
                })->orWhere(function ($q) use ($selectedDate) {
                    $q->where('is_recurrent', true)->where('due_date', '<=', $selectedDate);
                });
            })
            ->sum('amount');

        $totalExpenses = Expense::where('user_id', $user->id)
            ->where(function ($query) use ($month, $year, $selectedDate) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('date', $month)->whereYear('date', $year);
                })->orWhere(function ($q) use ($selectedDate) {
                    $q->where('is_recurrent', true)->where('date', '<=', $selectedDate);
                });
            })
            ->sum('amount');

        $totalDebts = Debt::where('user_id', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $totalSavings = \App\Models\SavingContribution::whereHas('parentSaving', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $balance = $totalRevenue - $totalExpenses;

        return view('mobile.analytics', compact(
            'totalRevenue',
            'totalExpenses',
            'totalDebts',
            'totalSavings',
            'balance',
            'month',
            'year'
        ));
    }
}
