<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    // Get all budgets for the current user for a specific month
    public function index(Request $request)
    {
        $user = $request->user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'amount' => $budget->amount,
                    'spent' => $budget->getSpentAmount(),
                    'remaining' => $budget->getRemainingAmount(),
                    'percentage_used' => $budget->getPercentageUsed(),
                    'is_over_budget' => $budget->isOverBudget(),
                    'month' => $budget->month,
                    'year' => $budget->year,
                ];
            });

        return response()->json($budgets);
    }

    // Create or update a budget
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        $user = $request->user();

        $budget = Budget::updateOrCreate(
            [
                'user_id' => $user->id,
                'category' => $validated['category'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            ['amount' => $validated['amount']]
        );

        return response()->json([
            'id' => $budget->id,
            'category' => $budget->category,
            'amount' => $budget->amount,
            'spent' => $budget->getSpentAmount(),
            'remaining' => $budget->getRemainingAmount(),
            'percentage_used' => $budget->getPercentageUsed(),
            'is_over_budget' => $budget->isOverBudget(),
            'month' => $budget->month,
            'year' => $budget->year,
        ], 201);
    }

    // Delete a budget
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $budget = Budget::where('user_id', $user->id)->findOrFail($id);
        $budget->delete();

        return response()->json(['message' => 'Budget deleted successfully']);
    }

    // Get budget summary/overview
    public function summary(Request $request)
    {
        $user = $request->user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $totalBudget = $budgets->sum('amount');
        $totalSpent = $budgets->sum(fn($b) => $b->getSpentAmount());
        $totalRemaining = max(0, $totalBudget - $totalSpent);

        return response()->json([
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => $totalRemaining,
            'percentage_used' => $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0,
            'categories_count' => $budgets->count(),
            'over_budget_count' => $budgets->filter(fn($b) => $b->isOverBudget())->count(),
        ]);
    }
}
