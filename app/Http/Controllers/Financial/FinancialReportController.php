<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\OrganizationActivity;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    // Display financial reports dashboard
    public function index(Request $request)
    {
        // Financial reports should consider all activities and transactions
        // General statistics
        $totalDonations = Donation::where('is_deleted', false)->sum('amount');

        $totalExpenses = Expense::sum('amount');

        $netAmount = $totalDonations - $totalExpenses;

        // Monthly statistics for charts
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');

            $monthlyDonations = Donation::where('is_deleted', false)
                ->whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');

            $monthlyExpenses = Expense::whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->sum('amount');

            $monthlyStats[] = [
                'month' => $monthName,
                'donations' => (float) $monthlyDonations,
                'expenses' => (float) $monthlyExpenses,
                'net' => (float) ($monthlyDonations - $monthlyExpenses)
            ];
        }

        // Distribution of donations by type
        $donationTypes = Donation::where('is_deleted', false)
            ->select('donation_type', DB::raw('SUM(amount) as total'))
            ->groupBy('donation_type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->donation_type,
                    'total' => (float) $item->total
                ];
            });

        // Top 5 activities by donations
        $topActivities = OrganizationActivity::with(['donations' => function ($q) {
            $q->where('is_deleted', false);
        }])
            ->get()
            ->map(function ($activity) {
                return [
                    'title' => $activity->title,
                    'donations' => $activity->donations->sum('amount'),
                    'expenses' => $activity->expenses->sum('amount'),
                    'net' => $activity->donations->sum('amount') - $activity->expenses->sum('amount')
                ];
            })
            ->sortByDesc('donations')
            ->take(5);

        return view('html.financial.reports.index', compact(
            'totalDonations',
            'totalExpenses',
            'netAmount',
            'monthlyStats',
            'donationTypes',
            'topActivities'
        ));
    }

    // Detailed activities report
    public function activitiesReport(Request $request)
    {
        $query = OrganizationActivity::with(['donations' => function ($q) {
            $q->where('is_deleted', false);
        }, 'expenses']);

        // Filter by date
        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        $activities = $query->get()->map(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'created_at' => $activity->created_at->format('Y-m-d'),
                'donations_count' => $activity->donations->count(),
                'donations_total' => $activity->donations->sum('amount'),
                'expenses_count' => $activity->expenses->count(),
                'expenses_total' => $activity->expenses->sum('amount'),
                'net_amount' => $activity->donations->sum('amount') - $activity->expenses->sum('amount')
            ];
        });

        return view('html.financial.reports.activities', compact('activities'));
    }

    // Detailed donations report
    public function donationsReport(Request $request)
    {
        $query = Donation::with(['donor', 'activity'])
            ->where('is_deleted', false);

        // Filter by date
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        // Filter by donation type
        if ($request->has('type') && $request->type) {
            $query->where('donation_type', $request->type);
        }

        $donations = $query->orderBy('date', 'desc')->paginate(25);

        // Additional statistics
        $totalAmount = $query->sum('amount');
        $donationsCount = $query->count();

        return view('html.financial.reports.donations', compact('donations', 'totalAmount', 'donationsCount'));
    }

    // Detailed expenses report
    public function expensesReport(Request $request)
    {
        $query = Expense::with(['activity']);

        // Filter by date
        if ($request->has('start_date') && $request->start_date) {
            $query->where('expense_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(25);

        // Additional statistics
        $totalAmount = $query->sum('amount');
        $expensesCount = $query->count();

        return view('html.financial.reports.expenses', compact('expenses', 'totalAmount', 'expensesCount'));
    }
}
