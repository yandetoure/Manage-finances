<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Forecast;
use Illuminate\Support\Facades\Auth;

class ForecastController extends Controller
{
    public function index()
    {
        $forecasts = Forecast::where('user_id', Auth::id())->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('mobile.forecasts.index', compact('forecasts'));
    }

    public function create()
    {
        return view('mobile.forecasts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'estimated_revenue' => 'required|numeric',
            'estimated_expenses' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();
        Forecast::create($validated);

        return redirect()->route('forecasts.index')->with('success', 'Prévision ajoutée !');
    }

    public function edit(string $id)
    {
        $forecast = Forecast::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.forecasts.edit', compact('forecast'));
    }

    public function update(Request $request, string $id)
    {
        $forecast = Forecast::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'estimated_revenue' => 'required|numeric',
            'estimated_expenses' => 'required|numeric',
        ]);

        $forecast->update($validated);

        return redirect()->route('forecasts.index')->with('success', 'Prévision mise à jour !');
    }

    public function destroy(string $id)
    {
        $forecast = Forecast::where('user_id', Auth::id())->findOrFail($id);
        $forecast->delete();

        return redirect()->route('forecasts.index')->with('success', 'Prévision supprimée !');
    }
}
