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

        $selectedDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        $totalGlobalRevenue = Revenue::where(function ($query) use ($month, $year, $selectedDate) {
            $query->where(function ($q) use ($month, $year) {
                $q->whereMonth('due_date', $month)->whereYear('due_date', $year);
            })->orWhere(function ($q) use ($selectedDate) {
                $q->where('is_recurrent', true)->where('due_date', '<=', $selectedDate);
            });
        })
            ->sum('amount');

        $totalGlobalExpenses = \App\Models\Expense::where(function ($query) use ($month, $year, $selectedDate) {
            $query->where(function ($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            })->orWhere(function ($q) use ($selectedDate) {
                $q->where('is_recurrent', true)->where('date', '<=', $selectedDate);
            });
        })
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
