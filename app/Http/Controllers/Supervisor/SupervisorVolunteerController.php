<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volunteer;

class SupervisorVolunteerController extends Controller
{

    // Display list of volunteers with filtering by status
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        // Fetch volunteers based on selected status
        $volunteers = Volunteer::when($status, function ($q, $status) {
            return $q->where('status', $status);
            // 12 requests per page
        })->latest()->paginate(12);

        // Volunteer statistics by status
        $counts = [
            'pending' => Volunteer::where('status', 'pending')->count(),
            'accepted' => Volunteer::where('status', 'accepted')->count(),
            'rejected' => Volunteer::where('status', 'rejected')->count(),
        ];

        return view('html.supervisor.volunteers.index', compact('volunteers', 'counts'));
    }

    // عرض تفاصيل طلب التطوع

    public function show($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        return view('html.supervisor.volunteers.show', compact('volunteer'));
    }

    // Update volunteer request status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,rejected',
        ]);

        $volunteer = Volunteer::findOrFail($id);
        // Update status
        $volunteer->status = $request->status;
        $volunteer->save();

        return redirect()->route('supervisor.volunteers.show', $id)
            ->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
