<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimPayment;
use Illuminate\Support\Facades\Auth;

class ClaimPaymentController extends Controller
{
    public function index(Request $request, $claimId)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($claimId);
        $payments = $claim->payments()->orderBy('payment_date', 'desc')->get();

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $claim = Claim::where('user_id', Auth::id())->findOrFail($validated['claim_id']);

        $payment = ClaimPayment::create($validated);

        // Check if fully paid
        $totalPaid = $claim->payments()->sum('amount');
        if ($totalPaid >= $claim->amount) {
            $claim->update(['status' => 'collected']);
        }

        return response()->json($payment, 201);
    }
}
