<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $managersCount = Manager::count();
        $recentManagers = Manager::orderByDesc('created_at')->limit(5)->get();
        return view('html.supervisor.dashboard', compact('managersCount', 'recentManagers'));
    }

    // Display list of managers
    public function viewManager(Request $request)
    {
        $managers = Manager::orderByDesc('created_at')->get();
        return view('html.supervisor.manager.managers', compact('managers'));
    }

    // Display specific manager details
    public function showManager($id)
    {
        $manager = Manager::findOrFail($id);
        return view('html.supervisor.manager.view_manager', compact('manager'));
    }

    // Add new manager
    public function addManager(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('html.supervisor.manager.add_manager');
        }

        $data = $request->validate([
            'username' => 'required|string|max:50|unique:managers,username',
            'email' => 'required|email|max:100|unique:managers,email',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'manager_type' => 'required|in:financial,activities',
            'status' => 'nullable|in:active,inactive'
        ]);

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_by'] = session('supervisor_id');
        $data['status'] = $data['status'] ?? 'active';

        Manager::create($data);

        return redirect()->route('supervisor.managers.index')->with('success', 'تم إضافة المدير بنجاح');
    }


    // Edit manager data
    public function editManager($id, Request $request)
    {
        $manager = Manager::findOrFail($id);

        if ($request->isMethod('get')) {
            return view('html.supervisor.manager.edit_manager', compact('manager'));
        }

        $data = $request->validate([
            'username' => 'required|string|max:50|unique:managers,username,' . $manager->id,
            'email' => 'required|email|max:100|unique:managers,email,' . $manager->id,
            'full_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            // 'manager_type' => 'required|in:financial,activities',
            'status' => 'required|in:active,inactive'
        ]);

        if ($request->filled('password')) {
            $data['password'] = password_hash($request->input('password'), PASSWORD_BCRYPT);
        }

        $manager->update($data);

        return redirect()->route('supervisor.managers.index')->with('success', 'تم تعديل بيانات المدير بنجاح');
    }

    // Delete manager
    public function deleteManager($id)
    {
        Manager::where('id', $id)->delete();
        return redirect()->route('supervisor.managers.index')->with('success', 'تم حذف المدير');
    }

    // Assign manager role
    public function assignRole(Request $request)
    {
        if ($request->isMethod('get')) {
            $managers = Manager::all();
            return view('html.supervisor.manager.assign_role', compact('managers'));
        }

        $data = $request->validate([
            'manager_id' => 'required|integer|exists:managers,id',
            'manager_type' => 'required|in:financial,activities'
        ]);

        Manager::where('id', $data['manager_id'])->update(['manager_type' => $data['manager_type']]);

        return redirect()->route('supervisor.assignRole')->with('success', 'تم تحديث دور المدير بنجاح');
    }
}
