<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    // Display volunteer registration form
    // Required data like countries and languages
    public function volunteerRegister()
    {

        $countries = json_decode(file_get_contents(public_path('data/countries_ar.json')), true);
        $languages = json_decode(file_get_contents(public_path('data/languages_ar.json')), true);
        return view('public.volunteer.register', compact('countries', 'languages'));
    }






    // Handle volunteer registration
    public function volunteerStore(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:volunteers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',
            'age' => 'required|integer|min:16',
            'nationality' => 'required|string',
            'address' => 'required|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'education_level' => 'nullable|string',
            'availability' => 'nullable|string',
            'preferred_roles' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*' => 'string',
            'emergency_contact' => 'nullable|string',
        ]);

        // Check if email already exists
        if (Volunteer::where('email', $request->email)->exists()) {
            return back()->withInput()->withErrors(['error' => 'هذا البريد الإلكتروني مسجل مسبقًا.']);
        }

        // Check if phone number already exists
        if (Volunteer::where('phone', $request->phone)->exists()) {
            return back()->withInput()->withErrors(['error' => 'رقم الهاتف مستخدم مسبقًا.']);
        }

        // Convert languages to text
        $data['languages'] = $request->languages ? implode(',', $request->languages) : null;

        // Encrypt password
        $data['password'] = bcrypt($data['password']);

        // Status
        $data['status'] = 'pending';

        // Save volunteer
        Volunteer::create($data);

        // Success message
        return redirect()->route('public.volunteer.register')
            ->with('success', 'تم إرسال طلبك بنجاح وسيتم مراجعته من قبل المشرف.');
    }
}
