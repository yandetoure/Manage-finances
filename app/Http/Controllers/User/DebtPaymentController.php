<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Debt;
use App\Models\DebtPayment;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class DebtPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $debt = Debt::where('user_id', Auth::id())->findOrFail($validated['debt_id']);

        DebtPayment::create($validated);

        // Create an expense entry to update the monthly balance
        Expense::create([
            'user_id' => $debt->user_id,
            'amount' => $validated['amount'],
            'category' => 'Remboursement dette - ' . $debt->creditor,
            'description' => $validated['note'] ?? $debt->description,
            'date' => $validated['payment_date'],
            'is_recurrent' => false,
            'category_id' => null,
        ]);

        return back()->with('success', 'Remboursement enregistré !');
    }
}
