<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = Revenue::with('category')->where('user_id', '=', Auth::id())->orderBy('due_date', 'desc')->get();
        return view('mobile.revenues.index', compact('revenues'));
    }

    public function create()
    {
        $categories = Category::where('type', '=', 'revenue')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '=', Auth::id());
            })->get();
        return view('mobile.revenues.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        if (empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $validated['user_id'] = Auth::id();
        Revenue::create($validated);

        return redirect()->route('revenues.index')->with('success', 'Revenu ajouté !');
    }

    public function edit(string $id)
    {
        $revenue = Revenue::where('user_id', '=', Auth::id())->findOrFail($id);
        $categories = Category::where('type', '=', 'revenue')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '=', Auth::id());
            })->get();
        return view('mobile.revenues.edit', compact('revenue', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $revenue = Revenue::where('user_id', '=', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'is_recurrent' => 'boolean',
            'frequency' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        if (array_key_exists('frequency', $validated) && empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $revenue->update($validated);

        return redirect()->route('revenues.index')->with('success', 'Revenu mis à jour !');
    }

    public function destroy(string $id)
    {
        $revenue = Revenue::where('user_id', '=', Auth::id())->findOrFail($id);
        $revenue->delete();

        return redirect()->route('revenues.index')->with('success', 'Revenu supprimé !');
    }
}
