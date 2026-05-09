<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
use Illuminate\Validation\Rule;

class ManagerProfileController extends Controller
{

    // Display manager profile
    public function profile(Request $request)
    {
     $manager = Auth::guard('manager')->user();

        return view('html.manager.profile.profile', compact('manager'));
    }

    // Edit manager profile

    // Display edit form (GET)
    public function editProfile(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        return view('html.manager.profile.edit_profile', compact('manager'));
    }

    public function updateProfile(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        try {
            $data = $request->validate([
                'full_name' => 'required|string|max:255',

                'username'  => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('managers', 'username')->ignore($manager->id),
                ],

                'email'     => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('managers', 'email')->ignore($manager->id),
                ],

                'phone'     => 'nullable|string|max:20',

                // Password is optional
                'password'  => 'nullable|string|min:8|confirmed',

                [
                    'username.unique' => 'Username is already taken.',
                    'email.unique' => 'Email address is already in use.',
                    'password.confirmed' => 'Password confirmation does not match.'
                ],
            ]);

            // Update password only if provided
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            } else {
                unset($data['password']); // Do not send it for update
            }

            // Update data
            $manager->update($data);

            return redirect()->route('manager.profile')
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('manager.profile.edit')
                ->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . $e->getMessage());
        }
    }
}
