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

        $totalRevenue = Revenue::where('user_id', '=', $user->id, 'and')->sum('amount');
        $totalExpenses = Expense::where('user_id', '=', $user->id, 'and')->sum('amount');
        $totalDebts = Debt::where('user_id', '=', $user->id, 'and')
            ->where('status', '!=', 'paid')
            ->get()
            ->sum('remaining');

        $totalClaims = \App\Models\Claim::where('user_id', '=', $user->id, 'and')
            ->where('status', '!=', 'collected')
            ->get()
            ->sum('remaining');

        $totalSavings = Saving::where('user_id', '=', $user->id, 'and')->sum('current_amount');

        $balance = $totalRevenue - $totalExpenses;

        return view('mobile.dashboard', compact('balance', 'totalDebts', 'totalSavings', 'totalRevenue', 'totalExpenses', 'totalClaims'));
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
            'currency' => 'required|string|in:FCFA,EUR,USD,GBP,JPY,CNY,CAD,CHF,AUD,ZAR',
            'language' => 'required|string|in:fr,en,es,pt,ar,de,it,zh',
            'notifications_enabled' => 'nullable|boolean',
            'theme' => 'nullable|string|in:dark,light',
            'accent_color' => 'required|string|in:blue,emerald,rose,amber,indigo,purple,zinc,orange,cyan,lime,pink,teal,night,forest,burgundy,cacao,midnight,sky,mint,sakura,lavender,sand,olive,sunset,ocean,cosmic,fire,tropical,berry,gold',
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
        $settings->theme = $request->has('theme_mode') ? 'light' : 'dark';
        $settings->accent_color = $validated['accent_color'];
        $settings->notifications_enabled = $request->has('notifications_enabled');
        $settings->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Paramètres mis à jour !',
                'settings' => $settings
            ]);
        }

        return back()->with('success', 'Profil et paramètres mis à jour !');
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $selectedDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        $totalRevenue = Revenue::where('user_id', '=', $user->id, 'and')
            ->where(function ($query) use ($month, $year, $selectedDate) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('due_date', '=', $month)->whereYear('due_date', '=', $year);
                })->orWhere(function ($q) use ($selectedDate) {
                    $q->where('is_recurrent', '=', true)->where('due_date', '<=', $selectedDate);
                });
            })
            ->sum('amount');

        $totalExpenses = Expense::where('user_id', '=', $user->id, 'and')
            ->where(function ($query) use ($month, $year, $selectedDate) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('date', '=', $month)->whereYear('date', '=', $year);
                })->orWhere(function ($q) use ($selectedDate) {
                    $q->where('is_recurrent', '=', true)->where('date', '<=', $selectedDate);
                });
            })
            ->sum('amount');

        $totalDebts = Debt::where('user_id', '=', $user->id, 'and')
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->get()
            ->sum('remaining');

        $totalSavings = \App\Models\SavingContribution::whereHas('parentSaving', function ($q) use ($user) {
            $q->where('user_id', '=', $user->id);
        })
            ->whereMonth('created_at', '=', $month, 'and')
            ->whereYear('created_at', '=', $year)
            ->sum('amount');

        $balance = $totalRevenue - $totalExpenses;

        // Data for Category Distribution (Selected Month)
        $expensesByCategory = Expense::where('user_id', '=', $user->id, 'and')
            ->where(function ($query) use ($month, $year, $selectedDate) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereMonth('date', '=', $month)->whereYear('date', '=', $year);
                })->orWhere(function ($q) use ($selectedDate) {
                    $q->where('is_recurrent', '=', true)->where('date', '<=', $selectedDate);
                });
            })
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->category->name ?? 'Autre',
                    'amount' => $items->sum('amount'),
                    'color' => $items->first()->category->color ?? '#94A3B8'
                ];
            })->values();

        // Data for 6-Month Trend
        $sixMonthTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::createFromDate($year, $month, 1)->subMonths($i);
            $m = $date->month;
            $y = $date->year;
            $sDate = $date->endOfMonth()->toDateString();

            $rev = Revenue::where('user_id', '=', $user->id, 'and')
                ->where(function ($query) use ($m, $y, $sDate) {
                    $query->where(function ($q) use ($m, $y) {
                        $q->whereMonth('due_date', '=', $m)->whereYear('due_date', '=', $y);
                    })->orWhere(function ($q) use ($sDate) {
                        $q->where('is_recurrent', '=', true)->where('due_date', '<=', $sDate);
                    });
                })->sum('amount');

            $exp = Expense::where('user_id', '=', $user->id, 'and')
                ->where(function ($query) use ($m, $y, $sDate) {
                    $query->where(function ($q) use ($m, $y) {
                        $q->whereMonth('date', '=', $m)->whereYear('date', '=', $y);
                    })->orWhere(function ($q) use ($sDate) {
                        $q->where('is_recurrent', '=', true)->where('date', '<=', $sDate);
                    });
                })->sum('amount');

            $sixMonthTrend[] = [
                'month' => $date->translatedFormat('M'),
                'revenue' => $rev,
                'expense' => $exp
            ];
        }

        return view('mobile.analytics', compact(
            'totalRevenue',
            'totalExpenses',
            'totalDebts',
            'totalSavings',
            'balance',
            'month',
            'year',
            'expensesByCategory',
            'sixMonthTrend'
        ));
    }
}
