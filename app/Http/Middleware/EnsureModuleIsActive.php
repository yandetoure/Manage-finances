<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        $setting = \App\Models\ModuleSetting::where('user_id', $user->id)
            ->where('module_name', $module)
            ->first();

        if ($setting && !$setting->is_enabled) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Module désactivé'], 403);
            }
            return redirect()->route('home')->with('error', 'Ce module est désactivé par l\'administrateur.');
        }

        return $next($request);
    }
}
