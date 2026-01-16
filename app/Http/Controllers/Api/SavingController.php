<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saving;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->savings()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'current_amount' => 'required|numeric',
            'target_amount' => 'required|numeric',
            'target_name' => 'required|string',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $saving = $request->user()->savings()->create($validated);

        return response()->json($saving, 201);
    }

    public function show(Saving $saving)
    {
        return $saving;
    }

    public function update(Request $request, Saving $saving)
    {
        if ($request->user()->id !== $saving->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $saving->update($request->all());
        return $saving;
    }

    public function destroy(Saving $saving)
    {
        if (request()->user()->id !== $saving->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $saving->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
