<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    // Display the list of volunteers
    public function getVolunteers(Request $request)
    {
        $query = Volunteer::query();

        // Search by name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $volunteers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('html.manager.volunteers.volunteers', compact('volunteers'));
    }

    // Show the form to add a new volunteer
    public function addVolunteer()
    {
        $countries = json_decode(file_get_contents(public_path('data/countries_ar.json')), true);
        $languages = json_decode(file_get_contents(public_path('data/languages_ar.json')), true);

        return view('html.manager.volunteers.add_volunteer', compact('countries', 'languages'));
    }

    // Save a new volunteer
    public function storeVolunteer(Request $request)
    {
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
            return back()->withInput()->withErrors(['error' => 'This email address is already registered.']);
        }

        // Check if phone number already exists
        if (Volunteer::where('phone', $request->phone)->exists()) {
            return back()->withInput()->withErrors(['error' => 'This phone number is already in use.']);
        }

        // Convert languages to text
        $data['languages'] = $request->languages ? implode(',', $request->languages) : null;

        // Encrypt password
        $data['password'] = bcrypt($data['password']);

        // Default status
        $data['status'] = 'active';

        // Save volunteer
        Volunteer::create($data);

        return redirect()->route('manager.volunteers.index')->with('success', 'تم إضافة المتطوع بنجاح');
    }

    // Display volunteer details
    public function viewVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);

        return view('html.manager.volunteers.view_volunteer', compact('volunteer'));
    }

    // Display edit volunteer form
    public function editVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $countries = json_decode(file_get_contents(public_path('data/countries_ar.json')), true);
        $languages = json_decode(file_get_contents(public_path('data/languages_ar.json')), true);

        return view('html.manager.volunteers.edit_volunteer', compact('volunteer', 'countries', 'languages'));
    }

    // Update volunteer data
    public function updateVolunteer(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('volunteers')->ignore($volunteer->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('volunteers')->ignore($volunteer->id)],
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
        if (Volunteer::where('email', $request->email)->where('id', '!=', $volunteer->id)->exists()) {
            return back()->withInput()->withErrors(['error' => 'This email address is already registered.']);
        }

        // Check if phone number already exists
        if (Volunteer::where('phone', $request->phone)->where('id', '!=', $volunteer->id)->exists()) {
            return back()->withInput()->withErrors(['error' => 'This phone number is already in use.']);
        }

        // Convert languages to text
        $data['languages'] = $request->languages ? implode(',', $request->languages) : null;

        $volunteer->update($data);

        return redirect()->route('manager.volunteers.index')->with('success', 'تم تحديث بيانات المتطوع بنجاح');
    }

    // Delete a volunteer
    public function destroyVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->route('manager.volunteers.index')->with('success', 'تم حذف المتطوع بنجاح');
    }
}
