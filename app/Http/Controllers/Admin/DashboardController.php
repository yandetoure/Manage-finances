<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Revenue;
use App\Models\Debt;
use App\Models\Saving;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount = User::count();

        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $totalGlobalRevenue = Revenue::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $totalGlobalExpenses = \App\Models\Expense::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $totalGlobalDebts = Debt::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        // For savings, we look at contributions in that month
        $totalGlobalSavings = \App\Models\SavingContribution::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $avgDebtPerUser = $userCount > 0 ? $totalGlobalDebts / $userCount : 0;

        return view('admin.dashboard', compact(
            'userCount',
            'totalGlobalRevenue',
            'totalGlobalExpenses',
            'totalGlobalDebts',
            'totalGlobalSavings',
            'avgDebtPerUser',
            'month',
            'year'
        ));
    }
}
