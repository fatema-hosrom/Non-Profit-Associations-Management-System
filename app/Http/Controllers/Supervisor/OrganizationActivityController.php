<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationActivity;
use App\Models\ActivityDonationSettings;
use App\Models\ActivityVolunteerRequirements;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\OrganizationEvent;
use Illuminate\Validation\Rule;
use Exception;


class OrganizationActivityController extends Controller
{

    // Display list of activities
    public function getActivities(Request $request)
    {

        $activities = OrganizationActivity::with('manager')->orderByDesc('created_at')
            ->get();
        return view('html.supervisor.activities.index', compact('activities'));
    }


    // Display specific activity details
    public function ShowActivity($id)
    {

        $activity = OrganizationActivity::where('id', $id)
            ->firstOrFail();
        return view('html.supervisor.activities.show', compact('activity'));
    }


    // Display list of organizations created by current manager
    public function getOrganizations(Request $request)
    {

        $organizations = Organization::with('manager')->orderByDesc('created_at')
            ->get();

        return view('html.supervisor.organizations.index', compact('organizations'));
    }



    // Display specific organization details
    public function showOrganization($id)
    {

        $org = Organization::with('manager')->where('id', $id)
            ->with('events')
            ->firstOrFail();
        return view('html.supervisor.organizations.show', compact('org'));
    }

    // عرض الفعاليات التابعة لجمعية معينة
    public function getEvents($organizationId)
    {

        $org = Organization::with('manager')->where('id', $organizationId)->firstOrFail();
        $events = $org->events()->orderBy('start_date')->get();
        return view('html.supervisor.organizations.events.index', compact('org', 'events'));
    }

    // عرض تفاصيل فعالية جمعية معينة
    public function viewEvent($id)
    {

        $event = OrganizationEvent::with('organization.manager')->where('id', $id)->firstOrFail();
        return view('html.supervisor.organizations.events.show', compact('event'));
    }
}
