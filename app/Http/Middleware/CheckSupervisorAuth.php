<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSupervisorAuth
{
    // Create Middleware to verify supervisor authentication
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('supervisor')->check()) {
            // Verify if supervisor ID exists in session
            if ($request->session()->has('supervisor_id')) {
                return $next($request);
            }
            return redirect()->route('auth.login');
        }
        return $next($request);
    }
}
