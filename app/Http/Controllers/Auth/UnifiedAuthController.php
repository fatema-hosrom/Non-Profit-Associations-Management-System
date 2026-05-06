<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedAuthController extends Controller
{

    /**
     * Display login page
     */
    public function showLogin()
    {
        return view('html.login.unified_login');
    }

    /**
     * Handle login attempt (for admins only)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        $credentials = $request->only('email', 'password');

        // 🟪 Attempt login as manager
        if (Auth::guard('manager')->attempt($credentials, $request->filled('remember'))) {

            $manager = Auth::guard('manager')->user();

            // Activity manager
            if ($manager->manager_type === 'activities') {

                $request->session()->regenerate(); // Only use here

                return redirect()->route('manager.dashboard');
            }

            // Financial manager
            if ($manager->manager_type === 'financial') {

                // Don't need logout from manager, keep both guards logged in
                Auth::guard('financial_manager')->login($manager, $request->filled('remember'));

                $request->session()->regenerate();

                return redirect()->route('financial.dashboard');
            }

            Auth::guard('manager')->logout();

            return back()->withErrors([
                'email' => 'نوع الحساب غير مدعوم'
            ])->withInput();
        }

        // 🟪 Attempt login as supervisor
        if (Auth::guard('supervisor')->attempt($credentials, $request->filled('remember'))) {

            $request->session()->regenerate();

            return redirect()->route('supervisor.dashboard');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة أو الحساب غير موجود'
        ])->withInput();
    }

    /**
     * Handle logout
     */
    public function logoutManager()
     {
    Auth::guard('manager')->logout();
    // Don't need logout from financial_manager here because it might be logged in too
    return redirect()->route('auth.login');
     }

public function logoutFinancial()
{
    Auth::guard('financial_manager')->logout();
    // Don't need logout from manager here because it might be logged in too
    return redirect()->route('auth.login');
}

public function logoutSupervisor()
{
    Auth::guard('supervisor')->logout();
    return redirect()->route('auth.login');
}
}
