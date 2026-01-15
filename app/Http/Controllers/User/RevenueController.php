<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = Revenue::where('user_id', Auth::id())->orderBy('due_date', 'desc')->get();
        return view('mobile.revenues.index', compact('revenues'));
    }

    public function create()
    {
        return view('mobile.revenues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        Revenue::create($validated);

        return redirect()->route('revenues.index')->with('success', 'Revenu ajouté !');
    }

    public function edit(string $id)
    {
        $revenue = Revenue::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.revenues.edit', compact('revenue'));
    }

    public function update(Request $request, string $id)
    {
        $revenue = Revenue::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $revenue->update($validated);

        return redirect()->route('revenues.index')->with('success', 'Revenu mis à jour !');
    }

    public function destroy(string $id)
    {
        $revenue = Revenue::where('user_id', Auth::id())->findOrFail($id);
        $revenue->delete();

        return redirect()->route('revenues.index')->with('success', 'Revenu supprimé !');
    }
}
