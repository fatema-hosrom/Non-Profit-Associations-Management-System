<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckManagerAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 🔴 Must be logged in
        if (!Auth::guard('manager')->check()) {
            return redirect()->route('auth.login');
        }

        $manager = Auth::guard('manager')->user();

        // 🔴 Protection from null (very important)
        if (!$manager) {
            Auth::guard('manager')->logout();
            return redirect()->route('auth.login');
        }

        // 🔴 Check manager type
        if ($manager->manager_type !== 'activities') {
            Auth::guard('manager')->logout();
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'You are not authorized to access']);
        }

        return $next($request);
    }
}
