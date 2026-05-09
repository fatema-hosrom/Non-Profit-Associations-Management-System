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



    // Display list of activities created by the current manager
    public function getActivities(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $query = OrganizationActivity::where('manager_id', $manager->id);

        // Search by title or description
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



    // Display details of a specific activity
    public function viewActivity($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        return view('html.manager.activities.view_activity', compact('activity'));
    }




    // Add a new activity

    // Display add activity form (GET)
    public function addActivity()
    {

        return view('html.manager.activities.add_activity');
    }

    // Handle add activity (POST)
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
            // Upload image
            if ($request->hasFile('image')) {
                $imagename = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('assets/images/activities'), $imagename);
                $data['image'] = $imagename;
            }

            $data['manager_id'] = $manager->id;

            $activity = OrganizationActivity::create($data);

            // Create sub-records based on activity type
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



    // Edit an existing activity
    // Display edit activity form (GET)
    public function editActivity($id, Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        return view('html.manager.activities.edit_activity', compact('activity'));
    }

    // Handle activity update (PUT)
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

        // Update image
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

            // Update or delete sub-records
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



    // Delete activity (DELETE)
    public function destroyActivity($id)
    {
        // This prevents the manager from deleting activities created by other managers
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();
        // Delete the image file associated with the activity if it exists
        if ($activity->image) {
            $imagePath = public_path('assets/images/activities/' . $activity->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file from the filesystem
            }
        }
        $activity->delete();

        return redirect()->route('manager.activities.index')->with('success', 'تم حذف الفعالية');
    }



    // Toggle activity publish status
    public function togglePublish($id)
    {
        $activity = OrganizationActivity::findOrFail($id);

        // If null or false → set to true
        $activity->is_published = $activity->is_published ? false : true;
        $activity->save();

        return redirect()->route('manager.activities.index')
            ->with('success', $activity->is_published ? 'تم إعلان الفعالية' : 'تم إيقاف الإعلان عن الفعالية');
    }

    // Change activity status
    public function toggleStatus($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        // Determine the next status in the cycle
        $currentStatus = $activity->status;
        $nextStatus = match ($currentStatus) {
            'draft' => 'active',
            'active' => 'closed',
            'closed' => 'draft',
            default => 'draft'
        };

        $activity->status = $nextStatus;
        $activity->save();

        // Status labels
        $statusMessages = [
            'draft' => 'Draft',
            'active' => 'Active',
            'closed' => 'Closed'
        ];

        return redirect()->route('manager.activities.index')
            ->with('success', 'Activity status changed to: ' . $statusMessages[$nextStatus]);
    }

    // Change activity status
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

        // Status labels
        $statusMessages = [
            'draft' => 'Draft',
            'active' => 'Active',
            'closed' => 'Closed'
        ];

        return redirect()->route('manager.activities.index')
            ->with('success', 'Activity status changed to: ' . $statusMessages[$newStatus]);
    }

    // Activity results

    // Manage activity results (view, add, and edit in one page)
    public function manageActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->first();

        // If results exist, show the edit page
        if ($results) {
            return view('html.manager.activities.edit_results', compact('activity', 'results'));
        }

        // If no results exist, show the add page
        return view('html.manager.activities.add_results', compact('activity'));
    }

    // Display add activity results form
    public function addActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        return view('html.manager.activities.add_results', compact('activity'));
    }

    // Save activity results
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
            // Upload images
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

            // Upload report file
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

    // Display edit activity results form
    public function editActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->firstOrFail();

        return view('html.manager.activities.edit_results', compact('activity', 'results'));
    }

    // Update activity results
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
            // Upload new images
            if ($request->hasFile('images')) {
                // Delete old images
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

            // Upload new report file
            if ($request->hasFile('report_file')) {
                // Delete old file
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

    // Delete activity results
    public function destroyActivityResults($id)
    {
        $manager = Auth::guard('manager')->user();
        $activity = OrganizationActivity::where('id', $id)
            ->where('manager_id', $manager->id)
            ->firstOrFail();

        $results = ActivityResult::where('activity_id', $id)->firstOrFail();

        // Delete image and video files
        if ($results->images) {
            $files = json_decode($results->images, true);
            if ($files) {
                foreach ($files as $file) {
                    $filePath = public_path('assets/files/activity_results/' . $file);
                    if (file_exists($filePath)) unlink($filePath);
                }
            }
        }

        // Delete report file
        if ($results->report_file) {
            $filePath = public_path('assets/files/activity_reports/' . $results->report_file);
            if (file_exists($filePath)) unlink($filePath);
        }

        $results->delete();

        return redirect()->route('manager.activities.index')->with('success', 'تم حذف نتائج الفعالية بنجاح');
    }
}
