<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationActivity;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\ActivityDonationSettings;
use App\Models\DonationCorrection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FinancialDonationController extends Controller
{
    // Display list of activities available for donations
    public function index(Request $request)
    {
        // financial manager should see all activities that accept donations, regardless of who created them

        $query = OrganizationActivity::with(['donationSettings', 'donations' => function ($q) {
            $q->where('is_deleted', false);
        }])
            ->withCount(['donations' => function ($q) {
                $q->where('is_deleted', false);
            }])
            ->whereHas('donationSettings', function ($q) {
                $q->where('donation_status', 'open');
            });

        // Filter by activity type
        if ($request->has('type') && $request->type !== '') {
            if ($request->type === 'donation') {
                // Donation only without volunteering
                $query->whereHas('donationSettings')
                    ->whereDoesntHave('volunteerRequirements', function ($q) {
                        $q->where('required_volunteers', '>', 0);
                    });
            } elseif ($request->type === 'both') {
                // Donation and volunteering
                $query->whereHas('donationSettings')
                    ->whereHas('volunteerRequirements', function ($q) {
                        $q->where('required_volunteers', '>', 0);
                    });
            }
        } else {
            // By default display activities that accept donations (open)
            $query->whereHas('donationSettings', function ($q) {
                $q->where('donation_status', 'open');
            });
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('html.financial.donations.activities', compact('activities'));
    }

    // Display donations for specific activity
    public function showActivityDonations($activityId, Request $request)
    {
        // any activity with donation settings can be viewed by the financial manager
        $activity = OrganizationActivity::with(['donationSettings'])
            ->where('id', $activityId)
            ->whereHas('donationSettings', function ($q) {
                $q->where('donation_status', 'open');
            })
            ->firstOrFail();

        $query = Donation::with(['donor'])
            ->where('activity_id', $activityId)
            ->where('is_deleted', false);

        // Search by donor name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('donor', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by donation type
        if ($request->has('type') && $request->type !== '') {
            $query->where('donation_type', $request->type);
        }

        $donations = $query->orderBy('date', 'desc')->paginate(15);
        $totalAmount = $query->sum('amount');

        return view('html.financial.donations.activity_donations', compact('activity', 'donations', 'totalAmount'));
    }

    // عرض نموذج إضافة تبرع
    public function createDonation($activityId, Request $request)
    {
        // allow creation if the activity has donation settings and campaign is open
        $activity = OrganizationActivity::with(['donationSettings'])
            ->where('id', $activityId)
            ->whereHas('donationSettings', function ($q) {
                $q->where('donation_status', 'open');
            })
            ->firstOrFail();

        $donors = Donor::orderBy('name')->get();

        return view('html.financial.donations.create_donation', compact('activity', 'donors'));
    }

    // Store new donation
    public function storeDonation(Request $request, $activityId)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->whereHas('donationSettings', function ($q) {
                $q->where('donation_status', 'open');
            })
            ->firstOrFail();

        $validated = $request->validate([
            'donor_name' => 'required_without:donor_id|string|max:255',
            'donor_email' => 'required_without:donor_id|email',
            'donor_phone' => 'nullable|string|max:20',
            'donor_address' => 'nullable|string',
            'donor_id' => 'nullable|exists:donors,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_type' => 'required|in:cash,online,check,other',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($validated, $activityId, $managerId) {
            $donorId = $validated['donor_id'];

            // إنشاء متبرع جديد إذا لم يكن موجوداً
            if (!$donorId) {
                $donor = Donor::create([
                    'name' => $validated['donor_name'],
                    'email' => $validated['donor_email'],
                    'phone' => $validated['donor_phone'] ?? null,
                    'address' => $validated['donor_address'] ?? null,
                ]);
                $donorId = $donor->id;
            }

            // إنشاء التبرع
            Donation::create([
                'donor_id' => $donorId,
                'activity_id' => $activityId,
                'amount' => $validated['amount'],
                'donation_type' => $validated['donation_type'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => $managerId,
                'is_deleted' => false
            ]);

            // تحديث المبلغ المجموع للتبرعات في إعدادات الفعالية
            $totalCollected = Donation::where('activity_id', $activityId)
                ->where('is_deleted', false)
                ->sum('amount');

            ActivityDonationSettings::where('activity_id', $activityId)
                ->update(['collected_amount' => $totalCollected]);
        });

        return redirect()->route('financial.donations.activity.show', $activityId)
            ->with('success', 'تم إضافة التبرع بنجاح');
    }

    // عرض نموذج تعديل تبرع
    public function editDonation($activityId, $donationId, Request $request)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $donation = Donation::with(['donor'])
            ->where('id', $donationId)
            ->where('activity_id', $activityId)
            ->where('is_deleted', false)
            ->firstOrFail();

        $donors = Donor::orderBy('name')->get();

        return view('html.financial.donations.edit_donation', compact('activity', 'donation', 'donors'));
    }

    // Update donation
    public function updateDonation(Request $request, $activityId, $donationId)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;

        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $donation = Donation::where('id', $donationId)
            ->where('activity_id', $activityId)
            ->where('is_deleted', false)
            ->firstOrFail();

        $validated = $request->validate([
            'donor_name' => 'required_without:donor_id|string|max:255',
            'donor_email' => 'required_without:donor_id|email',
            'donor_phone' => 'nullable|string|max:20',
            'donor_address' => 'nullable|string',
            'donor_id' => 'nullable|exists:donors,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_type' => 'required|in:cash,online,check,other',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'correction_reason' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($validated, $donation, $managerId) {
            $donorId = $validated['donor_id'];

            // إنشاء متبرع جديد إذا لم يكن موجوداً
            if (!$donorId) {
                $donor = Donor::create([
                    'name' => $validated['donor_name'],
                    'email' => $validated['donor_email'],
                    'phone' => $validated['donor_phone'] ?? null,
                    'address' => $validated['donor_address'] ?? null,
                ]);
                $donorId = $donor->id;
            }

            // Keep old amount to record correction if changed
            $oldAmount = $donation->amount;

            // Update donation
            $donation->update([
                'donor_id' => $donorId,
                'amount' => $validated['amount'],
                'donation_type' => $validated['donation_type'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // إذا تغيّر المبلغ، أنشئ سجل تصحيح
            if ($oldAmount != $validated['amount']) {
                DonationCorrection::create([
                    'donation_id' => $donation->id,
                    'reason' => $validated['correction_reason'] ?? 'تعديل المبلغ',
                    'corrected_amount' => $validated['amount'],
                    'correction_date' => now(),
                ]);

                // أعد حساب المجموع الكلي للتبرعات بعد التعديل
                $totalCollected = Donation::where('activity_id', $donation->activity_id)
                    ->where('is_deleted', false)
                    ->sum('amount');

                ActivityDonationSettings::where('activity_id', $donation->activity_id)
                    ->update(['collected_amount' => $totalCollected]);
            }
        });

        return redirect()->route('financial.donations.activity.show', $activityId)
            ->with('success', 'تم تحديث التبرع بنجاح');
    }

    // حذف تبرع (معلق - غير ظاهر في الواجهة)
    // public function destroyDonation($activityId, $donationId, Request $request)
    // {
    //     $managerId = $request->session()->get('financial_manager_id');
    //
    //     $activity = OrganizationActivity::where('id', $activityId)
    //         ->where('manager_id', $managerId)
    //         ->firstOrFail();
    //
    //     $donation = Donation::where('id', $donationId)
    //         ->where('activity_id', $activityId)
    //         ->where('is_deleted', false)
    //         ->firstOrFail();
    //
    //     $donation->update([
    //         'is_deleted' => true,
    //         'deleted_at' => now(),
    //         'deleted_by' => $managerId
    //     ]);
    //
    //     return redirect()->route('financial.donations.activity.show', $activityId)
    //         ->with('success', 'تم حذف التبرع بنجاح');
    // }
}
