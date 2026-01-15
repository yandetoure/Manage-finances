<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Debt;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function index()
    {
        $debts = Debt::with('payments')->where('user_id', Auth::id())->orderBy('due_date', 'asc')->get();
        return view('mobile.debts.index', compact('debts'));
    }

    public function create()
    {
        return view('mobile.debts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'creditor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        Debt::create($validated);

        return redirect()->route('debts.index')->with('success', 'Dette ajoutée !');
    }

    public function edit(string $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.debts.edit', compact('debt'));
    }

    public function update(Request $request, string $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'creditor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,late',
        ]);

        $debt->update($validated);

        return redirect()->route('debts.index')->with('success', 'Dette mise à jour !');
    }

    public function destroy(string $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Dette supprimée !');
    }

    public function updateStatus(Request $request, string $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,late',
        ]);

        $debt->update(['status' => $validated['status']]);

        return back()->with('success', 'Statut de la dette mis à jour !');
    }
}
