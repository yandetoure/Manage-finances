<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('category')->where('user_id', '=', Auth::id())->orderBy('date', 'desc')->get();
        return view('mobile.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::where('type', '=', 'expense')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '=', Auth::id());
            })->get();
        return view('mobile.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'date' => 'required|date',
        ]);

        if (isset($validated['date'])) {
            $validated['date'] = \Carbon\Carbon::parse($validated['date'])->format('Y-m-d');
        }

        if (empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $validated['user_id'] = Auth::id();

        // Remove old 'category' string field if it exists in DB as we use category_id now
        // But for consistency with the model, let's keep it handled if needed.

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Dépense ajoutée !');
    }

    public function edit(string $id)
    {
        $expense = Expense::where('user_id', '=', Auth::id())->findOrFail($id);
        $categories = Category::where('type', '=', 'expense')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '=', Auth::id());
            })->get();
        return view('mobile.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $expense = Expense::where('user_id', '=', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'date' => 'required|date',
        ]);

        if (isset($validated['date'])) {
            $validated['date'] = \Carbon\Carbon::parse($validated['date'])->format('Y-m-d');
        }

        if (array_key_exists('frequency', $validated) && empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour !');
    }

    public function destroy(string $id)
    {
        $expense = Expense::where('user_id', '=', Auth::id())->findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée !');
    }
}
