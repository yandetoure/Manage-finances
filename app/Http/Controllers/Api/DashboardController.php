<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Saving;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalRevenues = Revenue::where('user_id', $user->id)->sum('amount');
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');
        $totalSavings = Saving::where('user_id', $user->id)->sum('current_amount');

        return response()->json([
            'total_revenues' => $totalRevenues,
            'total_expenses' => $totalExpenses,
            'total_savings' => $totalSavings,
            'balance' => $totalRevenues - $totalExpenses,
            'recent_transactions' => $this->getRecentTransactions($user->id)
        ]);
    }

    private function getRecentTransactions($userId)
    {
        $revenues = Revenue::where('user_id', $userId)->latest()->take(5)->get()->map(function ($item) {
            $item->type = 'revenue';
            return $item;
        });

        $expenses = Expense::where('user_id', $userId)->latest()->take(5)->get()->map(function ($item) {
            $item->type = 'expense';
            return $item;
        });

        return $revenues->concat($expenses)->sortByDesc('created_at')->values()->take(5);
    }
}
