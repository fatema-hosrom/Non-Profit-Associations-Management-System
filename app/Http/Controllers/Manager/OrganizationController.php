<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationEvent;
use App\Models\Manager;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    // عرض قائمة الجمعيات التي أنشأها المدير الحالي
    public function getOrganizations(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $organizations = Organization::where('created_by', $managerId)
            ->orderByDesc('created_at')
            ->get();
        return view('html.manager.organizations.organizations', compact('organizations'));
    }


    // عرض تفاصيل جمعية
    public function viewOrganization($id)
    {
        //التحقق من ملكية الجمعية
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        // جلب الجمعية مع فعالياتها
        $org = Organization::where('id', $id)
            ->where('created_by', $managerId)
            ->with('events')
            ->firstOrFail();
        return view('html.manager.organizations.view_organization', compact('org'));
    }

    //=================================================================================
    // إضافة جمعية
    // عرض نموذج إنشاء جمعية (GET)
    public function addOrganization()
    {


        return view('html.manager.organizations.add_organization');
    }

    // معالجة إنشاء جمعية (POST)
    public function storeOrganization(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('organizations')->where(fn($q) => $q->where('created_by', $managerId))],
            'description' => 'nullable|string',
            'type' => 'required|in:local,external',
            'website_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $logoName = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move(public_path('assets/images/organizations'), $logoName);
                $data['logo'] = $logoName;
            } else {
                $data['logo'] = null;
            }

            $data['created_by'] = $managerId;
            Organization::create($data);

            return redirect()->route('manager.organizations.index')->with('success', 'تم إضافة الجمعية بنجاح');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }


    //=================================================================================
    // تعديل جمعية

    // عرض نموذج تعديل جمعية (GET)
    public function editOrganization($id, Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $org = Organization::where('id', $id)->where('created_by', $managerId)->firstOrFail();

        return view('html.manager.organizations.edit_organization', compact('org'));
    }

    // معالجة تحديث جمعية (PUT)
    public function updateOrganization(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $org = Organization::where('id', $id)->where('created_by', $managerId)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'type' => 'required|in:local,external',
            'website_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:active,inactive',
        ]);

        // معالجة رفع الشعار الجديد إذا تم تقديمه
        if ($request->hasFile('logo')) {
            $logoName = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('assets/images/organizations'), $logoName);
            $data['logo'] = $logoName;
            // حذف الصورة القديمة إن وجدت
            if ($org->logo) {
                $oldPath = public_path('assets/images/organizations/' . $org->logo);
                if (file_exists($oldPath)) unlink($oldPath);
            }
        } else {
            $data['logo'] = $org->logo;
        }
        // تحديث بيانات الجمعية
        $org->update($data);
        return redirect()->route('manager.organizations.index')->with('success', 'تم تحديث الجمعية بنجاح');
    }

    // حذف جمعية (DELETE)
    public function destroyOrganization($id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $org = Organization::where('id', $id)->where('created_by', $managerId)->firstOrFail();
        if ($org->logo) {
            $imagePath = public_path('assets/images/organizations/' . $org->logo);
            if (file_exists($imagePath)) unlink($imagePath);
        }
        $org->delete();
        return redirect()->route('manager.organizations.index')->with('success', 'تم حذف الجمعية');
    }





    //  ----------------------------------------------------------------------------------//

    //   الفعاليات تحت الجمعيات


    public function getEvents($organizationId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $org = Organization::where('id', $organizationId)->where('created_by', $managerId)->firstOrFail();
        $events = $org->events()->orderBy('start_date')->get();
        return view('html.manager.organizations.event.events', compact('org', 'events'));
    }

    // عرض تفاصيل فعالية جمعية
    public function viewEvent($id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $event = OrganizationEvent::where('id', $id)->where('created_by', $managerId)->with('organization')->firstOrFail();
        return view('html.manager.organizations.event.view_event', compact('event'));
    }

    // إضافة فعالية جديدة لجمعية
    // عرض نموذج إنشاء فعالية (GET)
    public function createEvent(Request $request, $organizationId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $org = Organization::where('id', $organizationId)->where('created_by', $managerId)->firstOrFail();
        return view('html.manager.organizations.event.add_event', compact('org'));
    }

    // معالجة إنشاء فعالية (POST)
    public function storeEvent(Request $request, $organizationId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $org = Organization::where('id', $organizationId)->where('created_by', $managerId)->firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'external_url' => 'nullable|url',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('assets/images/organization_events'), $imagename);
                $data['image'] = $imagename;
            } else {
                $data['image'] = null;
            }

            $data['organization_id'] = $org->id;
            $data['created_by'] = $managerId;

            OrganizationEvent::create($data);
            return redirect()->route('manager.organizations.events.index', ['orgId' => $org->id])->with('success', 'تم إضافة الفعالية بنجاح');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }


    // تعديل فعالية جمعية
    // عرض نموذج تعديل فعالية (GET)
    public function editEvent($id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $event = OrganizationEvent::where('id', $id)->where('created_by', $managerId)->firstOrFail();
        return view('html.manager.organizations.event.edit_event', compact('event'));
    }
    // معالجة تحديث فعالية (PUT)
    public function updateEvent(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $event = OrganizationEvent::where('id', $id)->where('created_by', $managerId)->firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'external_url' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $imagename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('assets/images/organization_events'), $imagename);
            // delete old image
            if ($event->image) {
                $oldPath = public_path('assets/images/organization_events/' . $event->image);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $data['image'] = $imagename;
        } else {
            $data['image'] = $event->image;
        }

        $event->update($data);
        return redirect()->route('manager.organizations.events.index', ['orgId' => $event->organization_id])->with('success', 'تم تحديث الفعالية بنجاح');
    }

    // حذف فعالية جمعية (DELETE)
    public function destroyEvent($id)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;
        $event = OrganizationEvent::where('id', $id)->where('created_by', $managerId)->firstOrFail();
        if ($event->image) {
            $imagePath = public_path('assets/images/organization_events/' . $event->image);
            if (file_exists($imagePath)) unlink($imagePath);
        }
        $orgId = $event->organization_id;
        $event->delete();
        return redirect()->route('manager.organizations.events.index', ['orgId' => $orgId])->with('success', 'تم حذف الفعالية');
    }
}
