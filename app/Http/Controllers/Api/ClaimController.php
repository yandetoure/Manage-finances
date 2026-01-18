<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->claims()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'debtor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        $claim = $request->user()->claims()->create($validated);

        return response()->json($claim, 201);
    }

    public function show(Claim $claim)
    {
        if (request()->user()->id !== $claim->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $claim;
    }

    public function update(Request $request, Claim $claim)
    {
        if ($request->user()->id !== $claim->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'debtor' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|string',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        $claim->update($validated);
        return $claim;
    }

    public function destroy(Claim $claim)
    {
        if (request()->user()->id !== $claim->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $claim->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
