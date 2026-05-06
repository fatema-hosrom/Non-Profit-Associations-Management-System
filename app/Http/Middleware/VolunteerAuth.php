<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerAuth
{
    /**
     * معالج للتحقق من تسجيل دخول المتطوع
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('volunteer')->check() ) {
            return $next($request);
        }

        return redirect()->route('public.home')
            ->with('error', 'يجب تسجيل الدخول أولاً');
    }
}
