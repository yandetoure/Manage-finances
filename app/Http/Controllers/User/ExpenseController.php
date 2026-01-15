<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        return view('mobile.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('mobile.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();
        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Dépense ajoutée !');
    }

    public function edit(string $id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.expenses.edit', compact('expense'));
    }

    public function update(Request $request, string $id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour !');
    }

    public function destroy(string $id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée !');
    }
}
