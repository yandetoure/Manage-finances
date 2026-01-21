<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimPayment;
use App\Models\Revenue;
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

        // Create a revenue entry to update the monthly balance
        Revenue::create([
            'user_id' => $claim->user_id,
            'amount' => $validated['amount'],
            'source' => 'Recouvrement - ' . $claim->debtor,
            'description' => $validated['note'] ?? $claim->description,
            'due_date' => $validated['payment_date'],
            'is_recurrent' => false,
            'category_id' => null,
        ]);

        return back()->with('success', 'Remboursement de créance enregistré !');
    }
}
