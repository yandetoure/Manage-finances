<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Debt;

class DebtController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->debts()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'creditor' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        $debt = $request->user()->debts()->create($validated);

        return response()->json($debt, 201);
    }

    public function show(Debt $debt)
    {
        if (request()->user()->id !== $debt->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $debt;
    }

    public function update(Request $request, Debt $debt)
    {
        if ($request->user()->id !== $debt->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'creditor' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|string',
        ]);

        if (isset($validated['due_date'])) {
            $validated['due_date'] = \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d');
        }

        $debt->update($validated);
        return $debt;
    }

    public function destroy(Debt $debt)
    {
        if (request()->user()->id !== $debt->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $debt->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
