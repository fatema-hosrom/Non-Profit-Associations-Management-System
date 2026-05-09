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
    // Display list of activities that need volunteers
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

        // Search
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

    // Display volunteers in a specific activity
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

        // Quick statistics
        $stats = [
            'total' => $assignments->count(),
            'pending' => $assignments->where('status', 'pending')->count(),
            'approved' => $assignments->where('status', 'approved')->count(),
            'rejected' => $assignments->where('status', 'rejected')->count(),
        ];

        return view('html.manager.activity_volunteers.manage_activity_volunteers', compact('activity', 'assignments', 'stats'));
    }

    // Add a volunteer to the activity
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

        // Check for existing record (whether active, rejected, or deleted)
        $existingAssignment = ActivityVolunteerAssignment::where('activity_id', $activityId)
            ->where('volunteer_id', $volunteerId)
            ->first();

        if ($existingAssignment) {
            if (in_array($existingAssignment->status, ['pending', 'approved'])) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'This volunteer already has a previous request for this activity'], 400);
                }
                return back()->with('error', 'This volunteer already has a previous request for this activity');
            }

            // If rejected or deleted, update the status instead of creating a new record
            $existingAssignment->update([
                'status' => 'approved',
                'decision_date' => now(),
                'joined_at' => now(),
                'request_date' => $existingAssignment->request_date ?? now(),
            ]);

            // Increment volunteer count for the activity
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements) {
                $requirements->increment('volunteers_count');
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Volunteer re-activated for the activity successfully']);
            }
            return back()->with('success', 'Volunteer re-activated for the activity successfully');
        }

        // Create a completely new record
        ActivityVolunteerAssignment::create([
            'activity_id' => $activityId,
            'volunteer_id' => $volunteerId,
            'status' => 'approved',
            'decision_date' => now(),
            'joined_at' => now(),
            'request_date' => now(),
        ]);

        // Increment volunteer count for the activity
        $requirements = $activity->volunteerRequirements()->first();
        if ($requirements) {
            $requirements->increment('volunteers_count');
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Volunteer added to the activity successfully']);
        }
        return back()->with('success', 'Volunteer added to the activity successfully');
    }

    // Approve a volunteer request
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

        // Increment volunteer count for the activity
        $requirements = $activity->volunteerRequirements()->first();
        if ($requirements) {
            $requirements->increment('volunteers_count');
        }

        return response()->json(['success' => true, 'message' => 'Volunteer request approved successfully']);
    }

    // Reject a volunteer request
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

        // Rejection reason is optional per user request
        // $request->validate([
        //     'rejection_reason' => 'required|string|max:500',
        // ]);

        $assignment->update([
            'status' => 'rejected',
            'decision_date' => now(),
            'rejection_reason' => $request->rejection_reason ?? null,
        ]);

        // Decrement volunteer count if previously approved
        if ($assignment->status === 'approved') {
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements && $requirements->volunteers_count > 0) {
                $requirements->decrement('volunteers_count');
            }
        }

        return response()->json(['success' => true, 'message' => 'Volunteer request rejected']);
    }

    // Remove a volunteer from the activity
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

        // Removal reason is no longer required
        // $request->validate([
        //     'removal_reason' => 'required|string|max:500',
        // ]);

        // Decrement volunteer count if previously approved
        if ($assignment->status === 'approved') {
            $requirements = $activity->volunteerRequirements()->first();
            if ($requirements && $requirements->volunteers_count > 0) {
                $requirements->decrement('volunteers_count');
            }
        }

        // Instead of permanent deletion, change status to "removed"
        $assignment->update([
            'status' => 'removed',
            'rejection_reason' => 'Removed by manager',
        ]);

        return response()->json(['success' => true, 'message' => 'Volunteer removed from the activity successfully']);
    }

    // View volunteer information
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

    // Get list of available volunteers to add
    public function getAvailableVolunteers($activityId)
    {
        $manager = Auth::guard('manager')->user();
        $managerId = $manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->where('manager_id', $managerId)
            ->firstOrFail();

        // Volunteers who have an active request (pending or approved) only
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

    // Download PDF file for volunteers with verification code
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

        // Generate verification codes for volunteers who don't have one yet
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
