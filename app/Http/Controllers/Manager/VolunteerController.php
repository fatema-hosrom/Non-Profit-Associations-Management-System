<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    // عرض قائمة المتطوعين
    public function getVolunteers(Request $request)
    {
        $query = Volunteer::query();

        // بحث حسب الاسم أو البريد
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

    // عرض نموذج إضافة متطوع جديد
    public function addVolunteer()
    {
        $countries = json_decode(file_get_contents(public_path('data/countries_ar.json')), true);
        $languages = json_decode(file_get_contents(public_path('data/languages_ar.json')), true);

        return view('html.manager.volunteers.add_volunteer', compact('countries', 'languages'));
    }

    // حفظ متطوع جديد
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

        // التحقق من البريد الإلكتروني
        if (Volunteer::where('email', $request->email)->exists()) {
            return back()->withInput()->withErrors(['error' => 'هذا البريد الإلكتروني مسجل مسبقًا.']);
        }

        // التحقق من رقم الهاتف
        if (Volunteer::where('phone', $request->phone)->exists()) {
            return back()->withInput()->withErrors(['error' => 'رقم الهاتف مستخدم مسبقًا.']);
        }

        // تحويل اللغات إلى نص
        $data['languages'] = $request->languages ? implode(',', $request->languages) : null;

        // تشفير كلمة المرور
        $data['password'] = bcrypt($data['password']);

        // الحالة الافتراضية
        $data['status'] = 'active';

        // حفظ المتطوع
        Volunteer::create($data);

        return redirect()->route('manager.volunteers.index')->with('success', 'تم إضافة المتطوع بنجاح');
    }

    // عرض تفاصيل متطوع
    public function viewVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);

        return view('html.manager.volunteers.view_volunteer', compact('volunteer'));
    }

    // عرض نموذج تعديل متطوع
    public function editVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $countries = json_decode(file_get_contents(public_path('data/countries_ar.json')), true);
        $languages = json_decode(file_get_contents(public_path('data/languages_ar.json')), true);

        return view('html.manager.volunteers.edit_volunteer', compact('volunteer', 'countries', 'languages'));
    }

    // تحديث بيانات متطوع
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

        // التحقق من البريد الإلكتروني
        if (Volunteer::where('email', $request->email)->where('id', '!=', $volunteer->id)->exists()) {
            return back()->withInput()->withErrors(['error' => 'هذا البريد الإلكتروني مسجل مسبقًا.']);
        }

        // التحقق من رقم الهاتف
        if (Volunteer::where('phone', $request->phone)->where('id', '!=', $volunteer->id)->exists()) {
            return back()->withInput()->withErrors(['error' => 'رقم الهاتف مستخدم مسبقًا.']);
        }

        // تحويل اللغات إلى نص
        $data['languages'] = $request->languages ? implode(',', $request->languages) : null;

        $volunteer->update($data);

        return redirect()->route('manager.volunteers.index')->with('success', 'تم تحديث بيانات المتطوع بنجاح');
    }

    // حذف متطوع
    public function destroyVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->delete();

        return redirect()->route('manager.volunteers.index')->with('success', 'تم حذف المتطوع بنجاح');
    }
}
