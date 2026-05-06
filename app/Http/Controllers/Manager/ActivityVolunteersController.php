<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationActivity;
use App\Models\Volunteer;
use App\Models\ActivityVolunteerAssignment;
use Illuminate\Support\Facades\Auth;
use TCPDF;

class ActivityVolunteersController extends Controller
{
    // عرض قائمة الفعاليات التي تحتاج متطوعين
    public function index(Request $request)
    {
        $manager = Auth::guard('manager')->user();

        $managerId = $manager->id;

        $query = OrganizationActivity::with(['volunteerRequirements', 'assignments'])
            ->withCount(['assignments'])
            ->where('manager_id', $managerId)
            ->where('is_published', true)
            ->whereHas('volunteerRequirements', function ($q) {
                $q->where('required_volunteers', '>', 0);
            });

        // البحث
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('html.manager.activity_volunteers.activity_volunteers', compact('activities'));
    }

    // عرض المتطوعين في فعالية معينة
    public function show($activityId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::with(['volunteerRequirements', 'assignments'])
            ->where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignments = ActivityVolunteerAssignment::with(['volunteer'])
            ->where('activity_id', $activityId)
            ->orderBy('request_date', 'desc')
            ->get();

        // إحصائيات سريعة
        $stats = [
            'total' => $assignments->count(),
            'pending' => $assignments->where('status', 'pending')->count(),
            'approved' => $assignments->where('status', 'approved')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
        ];

        return view('html.manager.activity_volunteers.manage_activity_volunteers', compact('activity', 'assignments', 'stats'));
    }

    // إضافة متطوع للفعالية
    public function assignVolunteer(Request $request, $activityId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
        ]);

        $volunteerId = $request->volunteer_id;

        // التحقق من وجود سجل سابق (سواء نشط أو مرفوض أو محذوف)
        $existingAssignment = ActivityVolunteerAssignment::where('activity_id', $activityId)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if ($existingAssignment) {
            if (in_array($existingAssignment->status, ['pending', 'approved'])) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'هذا المتطوع لديه طلب سابق لهذه الفعالية'], 400);
                }
                return back()->with('error', 'هذا المتطوع لديه طلب سابق لهذه الفعالية');
            }

            // إذا كان مرفوض أو محذوف، نقوم بتحديث الحالة بدلاً من إضافة سجل جديد
            $existingAssignment->update([
                'status' => 'approved',
                'decision_date' => now(),
                'joined_at' => now(),
                'request_date' => $existingAssignment->request_date ?? now(),
            ]);

            // زيادة عدد المتطوعين للفعالية
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements) {
                $requirements->increment('volunteers_count');
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'تم إعادة تفعيل انضمام المتطوع للفعالية بنجاح']);
            }
            return back()->with('success', 'تم إعادة تفعيل انضمام المتطوع للفعالية بنجاح');
        }

        // إنشاء سجل جديد تماماً
        ActivityVolunteerAssignment::create([
            'activity_id' => $activityId,
            'volunteer_id' => $volunteerId,
            'status' => 'approved',
            'decision_date' => now(),
            'joined_at' => now(),
            'request_date' => now(),
        ]);

        // زيادة عدد المتطوعين للفعالية
        $requirements = $activity->volunteerRequirements()->first();
        if ($requirements) {
            $requirements->increment('volunteers_count');
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'تم إضافة المتطوع للفعالية بنجاح']);
        }
        return back()->with('success', 'تم إضافة المتطوع للفعالية بنجاح');
    }

    // قبول طلب متطوع
    public function approveVolunteer($activityId, $assignmentId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignment = ActivityVolunteerAssignment::where('id', $assignmentId)
            ->where('activity_id', $activityId)
            ->where('status', 'pending')
            ->firstOrFail();

        $assignment->update([
            'status' => 'approved',
            'decision_date' => now(),
            'joined_at' => now(),
        ]);

        // زيادة عدد المتطوعين للفعالية
        $requirements = $activity->volunteerRequirements()->first();
        if ($requirements) {
            $requirements->increment('volunteers_count');
        }

        return response()->json(['success' => true, 'message' => 'تم قبول طلب المتطوع بنجاح']);
    }

    // رفض طلب متطوع
    public function rejectVolunteer(Request $request, $activityId, $assignmentId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignment = ActivityVolunteerAssignment::where('id', $assignmentId)
            ->where('activity_id', $activityId)
            ->whereIn('status', ['pending', 'approved'])
            ->firstOrFail();

        // لم نعد نطلب سبب الرفض بناءً على طلب المستخدم، أو يمكن جعله اختيارياً
        // $request->validate([
        //     'rejection_reason' => 'required|string|max:500',
        // ]);

        $assignment->update([
            'status' => 'rejected',
            'decision_date' => now(),
            'rejection_reason' => $request->rejection_reason ?? null,
        ]);

        // تقليل عدد المتطوعين للفعالية إذا كان مقبول
        if ($assignment->status === 'approved') {
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements && $requirements->volunteers_count > 0) {
                $requirements->decrement('volunteers_count');
            }
        }

        return response()->json(['success' => true, 'message' => 'تم رفض طلب المتطوع']);
    }

    // حذف متطوع من الفعالية
    public function removeVolunteer(Request $request, $activityId, $assignmentId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignment = ActivityVolunteerAssignment::where('id', $assignmentId)
            ->where('activity_id', $activityId)
            ->firstOrFail();

        // إزالة اشتراط سبب الحذف
        // $request->validate([
        //     'removal_reason' => 'required|string|max:500',
        // ]);

        // تقليل عدد المتطوعين للفعالية إذا كان مقبول
        if ($assignment->status === 'approved') {
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements && $requirements->volunteers_count > 0) {
                $requirements->decrement('volunteers_count');
            }
        }

        // بدلاً من الحذف النهائي، نقوم بتغيير الحالة إلى "removed"
        $assignment->update([
            'status' => 'removed',
            'rejection_reason' => 'تمت الإزالة من قبل المدير',
        ]);

        return response()->json(['success' => true, 'message' => 'تمت إزالة المتطوع من الفعالية بنجاح']);
    }

    // عرض معلومات المتطوع
    public function viewVolunteer($activityId, $assignmentId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignment = ActivityVolunteerAssignment::with(['volunteer'])
            ->where('id', $assignmentId)
            ->where('activity_id', $activityId)
            ->firstOrFail();

        return view('html.manager.activity_volunteers.view_volunteer_details', compact('activity', 'assignment'));
    }

    // الحصول على قائمة المتطوعين المتاحين للإضافة
    public function getAvailableVolunteers($activityId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        // المتطوعين الذين لديهم طلب نشط (pending أو approved) فقط
        $assignedVolunteerIds = ActivityVolunteerAssignment::where('activity_id', $activityId)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('volunteer_id')
            ->toArray();

        $volunteers = Volunteer::whereNotIn('id', $assignedVolunteerIds)
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name')
            ->get();

        return response()->json($volunteers);
    }

    // تحميل ملف PDF للمتطوعين مع كود التحقق
    public function downloadVolunteerPDF($activityId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::with(['volunteerRequirements', 'manager.organization'])
            ->where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        $assignments = ActivityVolunteerAssignment::with(['volunteer'])
            ->where('activity_id', $activityId)
            ->where('status', 'approved')
            ->get();

        // توليد أكواد تحقق للمتطوعين الذين ليس لديهم كود بعد
        foreach ($assignments as $assignment) {
            if (!$assignment->checkin_code) {
                $assignment->update([
                    'checkin_code' => ActivityVolunteerAssignment::generateUniqueCheckinCode(),
                ]);
            }
        }

        $html = view('html.manager.activity_volunteers.volunteers_pdf', [
            'activity' => $activity,
            'assignments' => $assignments,
            'date' => now()->format('Y-m-d H:i'),
        ])->render();

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(config('app.name', 'SAHAM'));
        $pdf->SetAuthor(config('app.name', 'SAHAM'));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setRTL(true);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 14);
        $pdf->AddPage();
        $pdf->SetFont('aealarabiya', '', 11);
        $pdf->writeHTML($html, true, false, true, false, '');

        $fileName = 'volunteers_'.$activity->id.'.pdf';

        return response($pdf->Output($fileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }
}
