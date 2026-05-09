<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizationActivity;
use App\Models\Expense;

class FinancialExpenseController extends Controller
{
    // Display list of activities with expenses (volunteering or both)
    public function index(Request $request)
    {
        // financial manager sees all activities that either have volunteer requirements or donation settings
        $query = OrganizationActivity::with(['expenses'])
            ->where(function ($q) {
                $q->whereHas('volunteerRequirements', function ($q2) {
                    $q2->where('required_volunteers', '>', 0);
                })->orWhereHas('donationSettings');
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
        return view('html.financial.expenses.activities', compact('activities'));
    }

    // Display expenses for specific activity
    public function showActivityExpenses($activityId, Request $request)
    {
        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $query = Expense::where('activity_id', $activityId);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('description', 'like', '%' . $search . '%');
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        $totalAmount = $query->sum('amount');

        return view('html.financial.expenses.activity_expenses', compact('activity', 'expenses', 'totalAmount'));
    }

    // Add expense
    public function createExpense(Request $request, $activityId = null)
    {
        // If activity ID provided allow creating for that specific activity
        if ($activityId) {
            $activity = OrganizationActivity::where('id', $activityId)
                ->firstOrFail();
            return view('html.financial.expenses.create_expense', compact('activity'));
        }

        // Otherwise provide list of all activities so user can choose one
        $activities = OrganizationActivity::orderBy('created_at', 'desc')->get();
        return view('html.financial.expenses.create_expense', compact('activities'));
    }

    public function storeExpense(Request $request, $activityId = null)
    {
        // Determine which activity the expense is for
        $rules = [
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'receipt_number' => 'required|string|max:50|unique:expenses,receipt_number',
            'receipt' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];

        if (! $activityId) {
            $rules['activity_id'] = 'required|numeric|exists:organization_activities,id';
        }

        $validated = $request->validate($rules);

        $aid = $activityId ?? $validated['activity_id'];

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/files/receipts'), $filename);
            $receiptPath = 'assets/files/receipts/' . $filename;
        }

        Expense::create([
            'activity_id' => $aid,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'receipt_number' => $validated['receipt_number'],
            'receipt' => $receiptPath,
        ]);

        return redirect()->route('financial.expenses.activity.show', $aid)
            ->with('success', 'تم إضافة المصروف بنجاح');
    }

    // Edit expense
    public function editExpense($activityId, $expenseId, Request $request)
    {
        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $expense = Expense::where('id', $expenseId)
            ->where('activity_id', $activityId)
            ->firstOrFail();

        return view('html.financial.expenses.edit_expense', compact('activity', 'expense'));
    }

    public function updateExpense(Request $request, $activityId, $expenseId)
    {
        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $expense = Expense::where('id', $expenseId)
            ->where('activity_id', $activityId)
            ->firstOrFail();

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'receipt_number' => 'required|string|max:50|unique:expenses,receipt_number,' . $expenseId,
            'receipt' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        $updateData = [
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'receipt_number' => $validated['receipt_number'],
        ];

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/files/receipts'), $filename);
            $receiptPath = 'assets/files/receipts/' . $filename;
            $updateData['receipt'] = $receiptPath;
        }

        $expense->update($updateData);

        return redirect()->route('financial.expenses.activity.show', $activityId)
            ->with('success', 'تم تحديث المصروف بنجاح');
    }

    // Delete the receipt file associated with the expense
    public function deleteReceipt($activityId, $expenseId, Request $request)
    {
        $activity = OrganizationActivity::where('id', $activityId)
            ->firstOrFail();

        $expense = Expense::where('id', $expenseId)
            ->where('activity_id', $activityId)
            ->firstOrFail();

        // Delete the file from the filesystem
        if ($expense->receipt && file_exists(public_path($expense->receipt))) {
            unlink(public_path($expense->receipt));
        }

        // Update the record by removing the file path
        $expense->update(['receipt' => null]);

        return redirect()->route('financial.expenses.activity.show', $activityId)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    // Delete expense (suspended)
    // public function destroyExpense($activityId, $expenseId, Request $request)
    // {
    //     $managerId = $request->session()->get('financial_manager_id');
    //     $expense = Expense::where('id', $expenseId)
    //         ->where('activity_id', $activityId)
    //         ->firstOrFail();
    //     $expense->delete();
    //     return redirect()->route('financial.expenses.activity.show', $activityId)
    //         ->with('success', 'Expense deleted successfully');
    // }
}
