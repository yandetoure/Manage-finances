<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->revenues()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'is_recurrent' => 'nullable|boolean',
            'frequency' => 'nullable|string',
        ]);

        if (empty($validated['frequency'])) {
            unset($validated['frequency']);
        }

        $revenue = $request->user()->revenues()->create($validated);

        return response()->json($revenue, 201);
    }

    public function show(Revenue $revenue)
    {
        return $revenue;
    }

    public function update(Request $request, Revenue $revenue)
    {
        if ($request->user()->id !== $revenue->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'source' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date',
            'is_recurrent' => 'nullable|boolean',
            'frequency' => 'nullable|string',
        ]);

        $revenue->update($validated);
        return $revenue;
    }

    public function destroy(Revenue $revenue)
    {
        if (request()->user()->id !== $revenue->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $revenue->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
