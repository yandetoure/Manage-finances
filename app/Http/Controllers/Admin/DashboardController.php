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
    public function index()
    {
        $userCount = User::count();
        $totalGlobalRevenue = Revenue::sum('amount');
        $totalGlobalDebts = Debt::where('status', 'pending')->sum('amount');
        $totalGlobalSavings = Saving::sum('current_amount');

        $avgDebtPerUser = $userCount > 0 ? $totalGlobalDebts / $userCount : 0;

        return view('admin.dashboard', compact('userCount', 'totalGlobalRevenue', 'totalGlobalDebts', 'totalGlobalSavings', 'avgDebtPerUser'));
    }
}
