<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Saving;
use App\Models\SavingContribution;
use Illuminate\Support\Facades\Auth;

class SavingContributionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'saving_id' => 'required|exists:savings,id',
            'amount' => 'required|numeric|min:0.01',
            'contribution_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $saving = Saving::where('user_id', Auth::id())->findOrFail($validated['saving_id']);

        SavingContribution::create($validated);

        // Update current_amount in saving goal
        $saving->increment('current_amount', $validated['amount']);

        return back()->with('success', 'Épargne ajoutée !');
    }
}
