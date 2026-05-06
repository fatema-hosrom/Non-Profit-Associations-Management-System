<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\OrganizationActivity;
use App\Models\ActivityVolunteerAssignment;
use Illuminate\Http\Request;

class VolunteerDashboardController extends Controller
{
    /**
     * Get the authenticated volunteer
     */
    private function getAuthVolunteer(Request $request)
    {
        if (auth('volunteer')->check()) {
            return auth('volunteer')->user();
        }

        // Support for legacy system if needed
        $volunteerId = $request->session()->get('volunteer_id');
        if ($volunteerId) {
            return Volunteer::find($volunteerId);
        }

        return null;
    }

    /**
     * Display volunteer dashboard
     */
    public function index(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        // Volunteer statistics
        $stats = [
            'pending_requests' => $volunteer->assignments()->where('status', 'pending')->count(),
            'approved_activities' => $volunteer->assignments()->where('status', 'approved')->count(),
            'completed_activities' => $volunteer->assignments()
                ->where('status', 'approved')
                ->whereHas('activity', function ($q) {
                    $q->where('end_date', '<', now());
                })->count(),
        ];

        return view('public.volunteer.dashboard', compact('volunteer', 'stats'));
    }

    /**
     * Display volunteer profile
     */
    public function profile(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        return view('public.volunteer.profile', compact('volunteer'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        // Validate input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:16',
            'nationality' => 'required|string',
            'address' => 'required|string',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'education_level' => 'nullable|string',
            'availability' => 'nullable|string',
            'preferred_roles' => 'nullable|string',
            'languages' => 'nullable|array',
            'emergency_contact' => 'nullable|string',
        ]);

        // Convert languages to text
        if (isset($data['languages'])) {
            $data['languages'] = implode(',', $data['languages']);
        }

        // Update data
        $volunteer->update($data);

        return redirect()->route('volunteer.profile')
            ->with('success', 'تم تحديث ملفك الشخصي بنجاح');
    }

    /**
     * Display available activities
     */
    public function availableActivities(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        // Fetch available activities (published and accepting volunteers)
        $activities = OrganizationActivity::where('is_published', true)
            ->where('start_date', '>', now())
            ->whereHas('volunteerRequirements', function ($q) {
                $q->where('required_volunteers', '>', 0);
            })
            ->with(['volunteerRequirements', 'assignments'])
            ->get();

        // Filter activities that volunteer hasn't requested yet
        $activities = $activities->filter(function ($activity) use ($volunteer) {
            return !$volunteer->assignments()
                ->where('activity_id', $activity->id)
                ->exists();
        });

        return view('public.volunteer.available-activities', compact('volunteer', 'activities'));
    }

    /**
     * Request to volunteer in an activity
     */
    public function requestVolunteer(Request $request, $activityId)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('volunteer.login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        // Verify activity exists
        $activity = OrganizationActivity::find($activityId);

        if (!$activity) {
            return back()->with('error', 'الفعالية غير موجودة');
        }

        // Verify no previous request exists (pending or approved)
        $existing = $volunteer->assignments()
            ->where('activity_id', $activityId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('volunteer.my-requests')->with('info', 'لديك طلب سابق لهذه الفعالية');
        }

        // Check rejected or removed statuses
        $rejected = $volunteer->assignments()
            ->where('activity_id', $activityId)
            ->whereIn('status', ['rejected', 'removed'])
            ->first();

        if ($rejected) {
            return back()->with('error', 'لا يمكنك التقديم لهذه الفعالية حالياً، يرجى التواصل مع المدير.');
        }

        // Get activity volunteer requirements
        $requirements = $activity->volunteerRequirements()->first();

        // If volunteer_mode = 'auto' → instant approval
        // If 'manual' → wait for manager approval
        if ($requirements && $requirements->volunteer_mode === 'auto') {
            $status = 'approved';
            $message = '✅ تم قبول طلبك في الفعالية مباشرة!';
            $decision_date = now();
            $joined_at = now();

            // Increment volunteer count
            if ($requirements) {
                $requirements->increment('volunteers_count');
            }
        } else {
            $status = 'pending';
            $message = '⏳ تم إرسال طلبك بنجاح، يرجى انتظار قبول المدير.';
            $decision_date = null;
            $joined_at = null;
        }

        // Create the record
        ActivityVolunteerAssignment::create([
            'activity_id' => $activityId,
            'volunteer_id' => $volunteer->id,
            'status' => $status,
            'request_date' => now(),
            'decision_date' => $decision_date,
            'joined_at' => $joined_at,
        ]);

        return redirect()->route('volunteer.my-requests')
            ->with('success', $message);
    }

    /**
     * Display volunteer requests
     */
    public function myRequests(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        // Fetch all volunteer requests
        $requests = $volunteer->assignments()
            ->with('activity')
            ->orderBy('request_date', 'desc')
            ->get();

        return view('public.volunteer.my-requests', compact('volunteer', 'requests'));
    }

    /**
     * Check-in verification via code
     */
    public function checkIn(Request $request, $activityId)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك'], 401);
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $submittedCode = strtoupper(trim((string) $request->input('code')));

        $assignment = ActivityVolunteerAssignment::where('activity_id', $activityId)
            ->where('volunteer_id', $volunteer->id)
            ->where('status', 'approved')
            ->first();

        if (!$assignment) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على اشتراك نشط لهذه الفعالية'], 404);
        }

        if ($assignment->checked_in_at) {
            return response()->json(['success' => false, 'message' => 'لقد قمت بتسجيل الحضور مسبقاً'], 400);
        }

        $storedCode = $assignment->checkin_code !== null
            ? strtoupper(trim((string) $assignment->checkin_code))
            : '';

        if ($storedCode === '' || $storedCode !== $submittedCode) {
            return response()->json(['success' => false, 'message' => 'كود التحقق غير صحيح'], 400);
        }

        $assignment->update([
            'checked_in_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => '✅ تم تسجيل الحضور بنجاح! انتقلت الفعالية إلى الفعاليات المنجزة.']);
    }

    /**
     * Display past activities
     */
    public function pastActivities(Request $request)
    {
        $volunteer = $this->getAuthVolunteer($request);

        if (!$volunteer) {
            return redirect()->route('public.home');
        }

        // Fetch only activities with successful check-in
        $activities = $volunteer->assignments()
            ->where('status', 'approved')
            ->whereNotNull('checked_in_at')
            ->with('activity')
            ->orderBy('checked_in_at', 'desc')
            ->get();

        return view('public.volunteer.past-activities', compact('volunteer', 'activities'));
    }
}
