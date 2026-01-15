<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ModuleSetting;

class ModuleController extends Controller
{
    public function index()
    {
        $users = User::with('moduleSettings')->get();
        return view('admin.modules.index', compact('users'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module_name' => 'required|string',
            'is_enabled' => 'required|boolean',
        ]);

        ModuleSetting::updateOrCreate(
            ['user_id' => $validated['user_id'], 'module_name' => $validated['module_name']],
            ['is_enabled' => $validated['is_enabled']]
        );

        return response()->json(['success' => true]);
    }
}
