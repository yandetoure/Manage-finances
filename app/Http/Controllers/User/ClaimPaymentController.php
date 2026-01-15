<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimPayment;
use Illuminate\Support\Facades\Auth;

class ClaimPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $claim = Claim::where('user_id', Auth::id())->findOrFail($validated['claim_id']);

        ClaimPayment::create($validated);

        return back()->with('success', 'Remboursement de créance enregistré !');
    }
}
