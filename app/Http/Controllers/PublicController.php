<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationActivity; // sahem activities
use App\Models\OrganizationEvent;

class PublicController extends Controller
{
    /**
     * Home page
     */

    public function home()
    {
        // Active organizations
        $organizations = Organization::where('status', 'active')
            ->withCount('events')
            ->get();

        // Sahem published activities
        $sahemActivities = OrganizationActivity::where('is_published', true)
            ->with(['donationSettings', 'volunteerRequirements'])
            ->orderBy('start_date')
            ->get();

        // Sahem statistics
        $sahemStats = [
            'activities' => $sahemActivities->count(),
            'donations' => $sahemActivities->sum(fn($a) => $a->donationSettings?->collected_amount ?? 0),
            'volunteers' => $sahemActivities->sum(fn($a) => $a->volunteerRequirements?->volunteers_count ?? 0),
        ];

        // Organization statistics
        $orgStats = [
            'organizations' => $organizations->count(),
            'events' => $organizations->sum('events_count'),
        ];
        // Latest upcoming organization events
        $recentOrgEvents = OrganizationEvent::with('organization')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')

            ->get();

        return view('public.home', compact(
            'organizations',
            'sahemActivities',
            'sahemStats',
            'orgStats',
            'recentOrgEvents'
        ));
    }

    /**
     * Display all organizations
     */
    public function organizations()
    {
        $organizations = Organization::where('status', 'active')->get();

        return view('public.organizations.index', compact('organizations'));
    }

    /**
     * Display a single organization with its events
     */
    public function showOrganization($id)
    {
        $organization = Organization::with('events')->findOrFail($id);

        return view('public.organizations.show', compact('organization'));
    }

    /**
     * Display all Sahem activities
     */
    public function sahemActivities()
    {
        $activities = OrganizationActivity::where('is_published', true)
            ->where('status', 'active')
            ->with(['donationSettings', 'volunteerRequirements'])
            ->orderBy('start_date')
            ->get();

        return view('public.activities.index', compact('activities'));
    }

    /**
     * Display Sahem activity details
     */
    public function showSahemActivity($id)
    {
        $activity = OrganizationActivity::with([
            'donationSettings',
            'volunteerRequirements',
            'manager'
        ])->findOrFail($id);

        return view('public.activities.show', compact('activity'));
    }

    /**
     * Display completed activities
     */
    public function completedActivities()
    {
        $completedActivities = OrganizationActivity::where('status', 'closed')
            ->with(['results', 'manager', 'donationSettings'])
            ->orderBy('end_date', 'desc')
            ->paginate(12);

        return view('public.activities.completed-activities', compact('completedActivities'));
    }

    /**
     * Display completed activity details
     */
    public function showCompletedActivity($id)
    {
        $activity = OrganizationActivity::where('status', 'closed')
            ->with(['results', 'manager', 'donationSettings'])
            ->findOrFail($id);

        return view('public.activities.completed-activity-details', compact('activity'));
    }

    // Display list of organization events

    public function organizationEvents()
    {
        $events = OrganizationEvent::with('organization')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('public.organizations.events_index', compact('events'));
    }

    /**
     * Display organization event details
     */
    public function showOrganizationEvent($id)
    {
        $event = OrganizationEvent::with('organization')->findOrFail($id);

        // Fetch other events for the same organization
        // amazonq-ignore-next-line
        $otherEvents = OrganizationEvent::where('organization_id', $event->organization_id)
            ->where('id', '!=', $event->id)
            ->get();

        return view('public.organizations.event_show', compact('event', 'otherEvents'));
    }
}
