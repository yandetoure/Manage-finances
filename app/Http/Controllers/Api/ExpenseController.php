<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->expenses()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'is_recurrent' => 'nullable|boolean',
            'frequency' => 'nullable|string',
        ]);

        if (empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $expense = $request->user()->expenses()->create($validated);

        return response()->json($expense, 201);
    }

    public function show(Expense $expense)
    {
        return $expense;
    }

    public function update(Request $request, Expense $expense)
    {
        if ($request->user()->id !== $expense->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'category' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'is_recurrent' => 'nullable|boolean',
            'frequency' => 'nullable|string',
        ]);

        $expense->update($validated);
        return $expense;
    }

    public function destroy(Expense $expense)
    {
        if (request()->user()->id !== $expense->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $expense->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
