<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;

class FinancialDashboardController extends Controller
{
    public function index(Request $request)
    {
        $financial_manager = Auth::guard('financial_manager')->user();
        $managerId = $financial_manager->id;

        // Financial Stats
        $totalDonationsCount = \App\Models\Donation::count();
        $totalDonationsAmount = \App\Models\Donation::sum('amount');
        $totalExpensesCount = \App\Models\Expense::count();
        $totalExpensesAmount = \App\Models\Expense::sum('amount');
        $netProfit = $totalDonationsAmount - $totalExpensesAmount;
        $activeActivities = \App\Models\OrganizationActivity::where('status', 'active')->count();
        $donorsCount = \App\Models\Donor::count();

        // Recent data
        $recentDonations = \App\Models\Donation::with(['activity', 'donor'])->latest()->take(5)->get();
        $recentExpenses = \App\Models\Expense::with('activity')->latest()->take(5)->get();

        return view('html.financial.dashboard', compact(
            'financial_manager',
            'totalDonationsCount',
            'totalDonationsAmount',
            'totalExpensesCount',
            'totalExpensesAmount',
            'netProfit',
            'activeActivities',
            'donorsCount',
            'recentDonations',
            'recentExpenses'
        ));
    }
}
