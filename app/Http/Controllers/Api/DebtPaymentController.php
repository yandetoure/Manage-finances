<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Support\Facades\Auth;

class DebtPaymentController extends Controller
{
    public function index(Request $request, $debtId)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($debtId);
        $payments = $debt->payments()->orderBy('payment_date', 'desc')->get();

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $debt = Debt::where('user_id', Auth::id())->findOrFail($validated['debt_id']);

        $payment = DebtPayment::create($validated);

        // Check if fully paid
        $totalPaid = $debt->payments()->sum('amount');
        if ($totalPaid >= $debt->amount) {
            $debt->update(['status' => 'paid']);
        }

        return response()->json($payment, 201);
    }
}
