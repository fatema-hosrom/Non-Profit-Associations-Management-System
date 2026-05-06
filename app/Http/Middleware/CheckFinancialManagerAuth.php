<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckFinancialManagerAuth
{
    // Ensure financial manager is logged in
    public function handle(Request $request, Closure $next)
    {
        // Must have authentication with financial_manager guard
        if (!Auth::guard('financial_manager')->check()) {
            if ($request->session()->has('financial_manager_id')) {
                return $next($request);
            }
            return redirect()->route('auth.login');
        }

        // Check manager type
        $manager = Auth::guard('financial_manager')->user();
        if ($manager && $manager->manager_type === 'financial') {
            return $next($request);
        }

        return redirect()->route('auth.login');
    }
}
