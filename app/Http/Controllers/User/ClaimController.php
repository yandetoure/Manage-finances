<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Claim;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::with('payments')->where('user_id', '=', Auth::id())->orderBy('due_date', 'asc')->get();
        return view('mobile.claims.index', compact('claims'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,late',
        ]);

        $claim->update(['status' => $validated['status']]);

        return back()->with('success', 'Statut de la créance mis à jour !');
    }

    public function create()
    {
        return view('mobile.claims.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'debtor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        Claim::create($validated);

        return redirect()->route('claims.index')->with('success', 'Créance ajoutée !');
    }

    public function edit(string $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.claims.edit', compact('claim'));
    }

    public function update(Request $request, string $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'debtor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,late',
        ]);

        $claim->update($validated);

        return redirect()->route('claims.index')->with('success', 'Créance mise à jour !');
    }

    public function destroy(string $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        $claim->delete();

        return redirect()->route('claims.index')->with('success', 'Créance supprimée !');
    }

    public function togglePaid(string $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        $claim->status = $claim->status == 'paid' ? 'pending' : 'paid';
        $claim->save();

        return back()->with('success', 'Statut de la créance mis à jour !');
    }
}
