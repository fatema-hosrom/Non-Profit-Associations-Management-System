<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerAuthController extends Controller
{
    /**
     * Open the login window (modal in the shared template) via redirect with a parameter in the URL.
     */
    public function showLogin(Request $request)
    {
        $target = route('public.home');

        $previous = url()->previous();
        if (
            $previous
            && $previous !== $request->fullUrl()
            && str_starts_with($previous, $request->getSchemeAndHttpHost())
        ) {
            $target = $previous;
        }

        $separator = str_contains($target, '?') ? '&' : '?';

        return redirect()->to($target . $separator . 'open_volunteer_login=1');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        // Login using guard
        if (!Auth::guard('volunteer')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
        }

        $volunteer = Auth::guard('volunteer')->user();

        // Verify account status
        if ($volunteer->status !== 'active') {
            Auth::guard('volunteer')->logout();
            return back()->with('error', 'الحساب غير مفعل');
        }

        // Secure session
        $request->session()->migrate(true);

        return redirect()->route('volunteer.dashboard')
            ->with('success', 'تم تسجيل الدخول بنجاح');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('volunteer')->logout();

        return redirect()->route('public.home')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
