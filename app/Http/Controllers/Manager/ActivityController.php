<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationActivity;
use App\Models\ActivityDonationSettings;
use App\Models\ActivityVolunteerRequirements;
use App\Models\Manager;
use App\Models\ActivityResult;
use Illuminate\Support\Facades\Auth;
use Exception;

class ActivityController extends Controller
{

    public function dashboard()
    {
       $manager = Auth::guard('manager')->user();

        $recent_activities = OrganizationActivity::where('manager_id', $manager->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        $activitiesCount = OrganizationActivity::where('manager_id', $manager->id)->count();
        return view('html.manager.dashboard', compact('activitiesCount', 'recent_activities', 'manager'));
    }



    // عرض قائمة الفعاليات التي أنشأها المدير الحالي
    public function getActivities(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $query = OrganizationActivity::where('manager_id', $manager->id);

        // بحث حسب العنوان أو الوصف
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $activities = $query->orderByDesc('created_at')->get();

        return view('html.manager.activities.activities', compact('activities'));
    }



    // عرض تفاصيل فعالية معينة
    public function viewActivity($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        return view('html.manager.activities.view_activity', compact('activity'));
    }




    // إضافة فعالية جديدة


    // عرض نموذج إضافة فعالية (GET)
    public function addActivity()
    {

        return view('html.manager.activities.add_activity');
    }

    // معالجة إضافة فعالية (POST)
    public function storeActivity(Request $request)
    {
        $manager = Auth::guard('manager')->user();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_type' => 'required|in:donation,volunteer,both',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            // رفع الصورة
            if ($request->hasFile('image')) {
                $imagename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('assets/images/activities'), $imagename);
                $data['image'] = $imagename;
            }

            $data['manager_id'] = $manager->id;

            $activity = OrganizationActivity::create($data);

            // إنشاء السجلات الفرعية حسب النوع
            if ($data['activity_type'] === 'donation' || $data['activity_type'] === 'both') {
                ActivityDonationSettings::create([
                    'activity_id' => $activity->id,
                    'target_amount' => $request->input('target_amount'),
                    'collected_amount' => 0,
                    'donation_status' => 'open',
                ]);
            }

            if ($data['activity_type'] === 'volunteer' || $data['activity_type'] === 'both') {
                ActivityVolunteerRequirements::create([
                    'activity_id' => $activity->id,
                    'required_volunteers' => $request->input('required_volunteers'),
                    'volunteers_count' => 0,
                    'volunteer_mode' => $request->input('volunteer_mode', 'manual'),
                    'min_age' => $request->input('min_age'),
                    'gender_requirement' => $request->input('gender_requirement', 'both'),
                    'skills_required' => $request->input('skills_required'),
                    'min_hours' => $request->input('min_hours'),
                ]);
            }

            return redirect()->route('manager.activities.index')->with('success', 'تم إضافة الفعالية بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الفعالية: ' . $e->getMessage());
        }
    }



    // تعديل فعالية موجودة
    // عرض نموذج تعديل فعالية (GET)
    public function editActivity($id, Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        return view('html.manager.activities.edit_activity', compact('activity'));
    }

    // معالجة تحديث فعالية (PUT)
    public function updateActivity(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_type' => 'required|in:donation,volunteer,both',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // تحديث الصورة
        if ($request->hasFile('image')) {
            $imagename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('assets/images/activities'), $imagename);
            if ($activity->image) {
                $oldPath = public_path('assets/images/activities/' . $activity->image);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $data['image'] = $imagename;
        }
        try {

            $activity->update($data);

            // تحديث أو حذف السجلات الفرعية
            if ($data['activity_type'] === 'donation') {
                $activity->volunteerRequirements()?->delete();
                $activity->donationSettings()->updateOrCreate(
                    ['activity_id' => $activity->id],
                    [
                        'target_amount' => $request->input('target_amount'),
                        'donation_status' => $request->input('donation_status', 'open'),
                    ]
                );
            } elseif ($data['activity_type'] === 'volunteer') {
                $activity->donationSettings()?->delete();
                $activity->volunteerRequirements()->updateOrCreate(
                    ['activity_id' => $activity->id],
                    [
                        'required_volunteers' => $request->input('required_volunteers'),
                        'volunteer_mode' => $request->input('volunteer_mode', 'manual'),
                        'min_age' => $request->input('min_age'),
                        'gender_requirement' => $request->input('gender_requirement', 'both'),
                        'skills_required' => $request->input('skills_required'),
                        'min_hours' => $request->input('min_hours'),
                    ]
                );
            } elseif ($data['activity_type'] === 'both') {
                $activity->donationSettings()->updateOrCreate(
                    ['activity_id' => $activity->id],
                    [
                        'target_amount' => $request->input('target_amount'),
                        'donation_status' => $request->input('donation_status', 'open'),
                    ]
                );
                $activity->volunteerRequirements()->updateOrCreate(
                    ['activity_id' => $activity->id],
                    [
                        'required_volunteers' => $request->input('required_volunteers'),
                        'volunteer_mode' => $request->input('volunteer_mode', 'manual'),
                        'min_age' => $request->input('min_age'),
                        'gender_requirement' => $request->input('gender_requirement'),
                        'skills_required' => $request->input('skills_required'),
                        'min_hours' => $request->input('min_hours'),
                    ]
                );
            }
            return redirect()->route('manager.activities.index')->with('success', 'تم تحديث الفعالية بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الفعالية: ' . $e->getMessage());
        }
    }



    // حذف فعالية (DELETE)
    public function destroyActivity($id)
    {
        // هذا يمنع المدير من حذف فعاليات أنشأها مديرون آخرون
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        // حذف ملف الصورة المرتبط بالفعالية إذا كان موجودًا
        if ($activity->image) {
            $imagePath = public_path('assets/images/activities/' . $activity->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // حذف الملف من النظام
            }
        }
        $activity->delete();

        return redirect()->route('manager.activities.index')->with('success', 'تم حذف الفعالية');
    }



    //  تبديل حالة النشر للفعالية
    public function togglePublish($id)
    {
        $activity = OrganizationActivity::findOrFail($id);

        // إذا كان null أو false → نجعله true
        $activity->is_published = $activity->is_published ? false : true;
        $activity->save();

        return redirect()->route('manager.activities.index')
            ->with('success', $activity->is_published ? 'تم إعلان الفعالية' : 'تم إيقاف الإعلان عن الفعالية');
    }

    // تغيير حالة الفعالية
    public function toggleStatus($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        // تحديد الحالة التالية في الدورة
        $currentStatus = $activity->status;
        $nextStatus = match ($currentStatus) {
            'draft' => 'active',
            'active' => 'closed',
            'closed' => 'draft',
            default => 'draft'
        };

        $activity->status = $nextStatus;
        $activity->save();

        // رسائل النجاح بالعربية
        $statusMessages = [
            'draft' => 'مسودة',
            'active' => 'نشطة',
            'closed' => 'مغلقة'
        ];

        return redirect()->route('manager.activities.index')
            ->with('success', 'تم تغيير حالة الفعالية إلى: ' . $statusMessages[$nextStatus]);
    }

    // تغيير حالة الفعالية
    public function changeStatus(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $newStatus = $request->status;
        $activity->status = $newStatus;
        $activity->save();

        // رسائل النجاح بالعربية
        $statusMessages = [
            'draft' => 'مسودة',
            'active' => 'نشطة',
            'closed' => 'مغلقة'
        ];

        return redirect()->route('manager.activities.index')
            ->with('success', 'تم تغيير حالة الفعالية إلى: ' . $statusMessages[$newStatus]);
    }

    // نتائج الفعاليات

    // إدارة نتائج الفعالية (عرض وإضافة وتعديل في صفحة واحدة)
    public function manageActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->first();

        // إذا كانت هناك نتائج، عرض صفحة التعديل
        if ($results) {
            return view('html.manager.activities.edit_results', compact('activity', 'results'));
        }

        // إذا لم تكن هناك نتائج، عرض صفحة الإضافة
        return view('html.manager.activities.add_results', compact('activity'));
    }

    // عرض نموذج إضافة نتائج الفعالية
    public function addActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        return view('html.manager.activities.add_results', compact('activity'));
    }

    // حفظ نتائج الفعالية
    public function storeActivityResults(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $data = $request->validate([
            'total_volunteers' => 'nullable|integer|min:0',
            'total_hours' => 'nullable|integer|min:0',
            'attendance_count' => 'nullable|integer|min:0',
            'goals_achieved' => 'nullable|string',
            'challenges' => 'nullable|string',
            'notes' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // رفع الصور
            $imageNames = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('assets/images/activity_results'), $imageName);
                        $imageNames[] = $imageName;
                    }
                }
            }
            $data['images'] = implode("\n", $imageNames);

            // رفع ملف التقرير
            if ($request->hasFile('report_file')) {
                $fileName = uniqid() . '.' . $request->file('report_file')->getClientOriginalExtension();
                $request->file('report_file')->move(public_path('assets/files/activity_reports'), $fileName);
                $data['report_file'] = $fileName;
            }

            $data['activity_id'] = $id;
            $data['created_by'] = $manager->id;

            ActivityResult::create($data);

            return redirect()->route('manager.activities.index')->with('success', 'تم إضافة نتائج الفعالية بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة نتائج الفعالية: ' . $e->getMessage());
        }
    }

    // عرض نموذج تعديل نتائج الفعالية
    public function editActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->firstOrFail();

        return view('html.manager.activities.edit_results', compact('activity', 'results'));
    }

    // تحديث نتائج الفعالية
    public function updateActivityResults(Request $request, $id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->firstOrFail();

        $data = $request->validate([
            'total_volunteers' => 'nullable|integer|min:0',
            'total_hours' => 'nullable|integer|min:0',
            'attendance_count' => 'nullable|integer|min:0',
            'goals_achieved' => 'nullable|string',
            'challenges' => 'nullable|string',
            'notes' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // رفع الصور الجديدة
            if ($request->hasFile('images')) {
                // حذف الصور القديمة
                if ($results->images) {
                    $oldImages = explode("\n", $results->images);
                    foreach ($oldImages as $oldImage) {
                        $oldPath = public_path('assets/images/activity_results/' . trim($oldImage));
                        if (file_exists($oldPath)) unlink($oldPath);
                    }
                }

                $imageNames = [];
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('assets/images/activity_results'), $imageName);
                        $imageNames[] = $imageName;
                    }
                }
                $data['images'] = implode("\n", $imageNames);
            }

            // رفع ملف التقرير الجديد
            if ($request->hasFile('report_file')) {
                // حذف الملف القديم
                if ($results->report_file) {
                    $oldPath = public_path('assets/files/activity_reports/' . $results->report_file);
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $fileName = uniqid() . '.' . $request->file('report_file')->getClientOriginalExtension();
                $request->file('report_file')->move(public_path('assets/files/activity_reports'), $fileName);
                $data['report_file'] = $fileName;
            }

            $results->update($data);

            return redirect()->route('manager.activities.index')->with('success', 'تم تحديث نتائج الفعالية بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث نتائج الفعالية: ' . $e->getMessage());
        }
    }

    // حذف نتائج الفعالية
    public function destroyActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->firstOrFail();

        // حذف ملفات الصور والفيديوهات
        if ($results->images) {
            $files = json_decode($results->images, true);
            if ($files) {
                foreach ($files as $file) {
                    $filePath = public_path('assets/files/activity_results/' . $file);
                    if (file_exists($filePath)) unlink($filePath);
                }
            }
        }

        // حذف ملف التقرير
        if ($results->report_file) {
            $filePath = public_path('assets/files/activity_reports/' . $results->report_file);
            if (file_exists($filePath)) unlink($filePath);
        }

        $results->delete();

        return redirect()->route('manager.activities.index')->with('success', 'تم حذف نتائج الفعالية بنجاح');
    }
}
