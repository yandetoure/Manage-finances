<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Saving;
use Illuminate\Support\Facades\Auth;

class SavingController extends Controller
{
    public function index()
    {
        $savings = Saving::with('contributions')->where('user_id', Auth::id())->get();
        return view('mobile.savings.index', compact('savings'));
    }

    public function create()
    {
        return view('mobile.savings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_name' => 'required|string',
            'target_amount' => 'required|numeric',
            'current_amount' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['current_amount'] = $validated['current_amount'] ?? 0;
        Saving::create($validated);

        return redirect()->route('savings.index')->with('success', 'Épargne ajoutée !');
    }

    public function edit(string $id)
    {
        $saving = Saving::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.savings.edit', compact('saving'));
    }

    public function update(Request $request, string $id)
    {
        $saving = Saving::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'target_name' => 'required|string',
            'target_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'deadline' => 'nullable|date',
        ]);

        $saving->update($validated);

        return redirect()->route('savings.index')->with('success', 'Épargne mise à jour !');
    }

    public function destroy(string $id)
    {
        $saving = Saving::where('user_id', Auth::id())->findOrFail($id);
        $saving->delete();

        return redirect()->route('savings.index')->with('success', 'Épargne supprimée !');
    }
}
