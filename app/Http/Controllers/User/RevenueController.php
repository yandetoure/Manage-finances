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
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        Revenue::create($validated);

        return redirect()->route('revenues.index')->with('success', 'Revenu ajouté !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
