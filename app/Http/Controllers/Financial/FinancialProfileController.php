<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FinancialProfileController extends Controller
{
    public function show(Request $request)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;
        $manager = Manager::findOrFail($managerId);
        return view('html.financial.profile.profile', compact('manager'));
    }


    public function edit(Request $request)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;
        $manager = Manager::findOrFail($managerId);
        return view('html.financial.profile.edit_profile', compact('manager'));
    }

    public function update(Request $request)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;
        $manager = Manager::findOrFail($managerId);

        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|unique:managers,email,' . $manager->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $manager->full_name = $data['full_name'];
        $manager->email = $data['email'];
        $manager->phone = $data['phone'];
        if (!empty($data['password'])) {
            $manager->password = Hash::make($data['password']);
        }
        $manager->save();

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
