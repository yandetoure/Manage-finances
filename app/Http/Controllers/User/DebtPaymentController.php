<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Debt;
use App\Models\DebtPayment;
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

        // Update debt status if fully paid (optional logic)
        // For now, just stay on the same page.

        return back()->with('success', 'Remboursement enregistré !');
    }
}
