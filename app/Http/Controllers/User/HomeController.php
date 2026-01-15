<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Saving;
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
}
