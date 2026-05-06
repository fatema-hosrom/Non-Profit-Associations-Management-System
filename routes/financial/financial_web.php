<?php

use App\Http\Middleware\CheckFinancialManagerAuth;
use Illuminate\Support\Facades\Route;

// Financial manager routes
Route::middleware([CheckFinancialManagerAuth::class])->prefix('financial')->name('financial.')->group(function () {
    Route::get('/', [App\Http\Controllers\Financial\FinancialDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Financial\FinancialProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [App\Http\Controllers\Financial\FinancialProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Financial\FinancialProfileController::class, 'update'])->name('profile.update');
    // Donations management
    Route::get('/donations', [App\Http\Controllers\Financial\FinancialDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/activity/{id}', [App\Http\Controllers\Financial\FinancialDonationController::class, 'showActivityDonations'])->name('donations.activity.show');
    Route::get('/donations/activity/{id}/create', [App\Http\Controllers\Financial\FinancialDonationController::class, 'createDonation'])->name('donations.activity.create');
    Route::post('/donations/activity/{id}', [App\Http\Controllers\Financial\FinancialDonationController::class, 'storeDonation'])->name('donations.activity.store');
    Route::get('/donations/activity/{aid}/edit/{did}', [App\Http\Controllers\Financial\FinancialDonationController::class, 'editDonation'])->name('donations.activity.edit');
    Route::put('/donations/activity/{aid}/edit/{did}', [App\Http\Controllers\Financial\FinancialDonationController::class, 'updateDonation'])->name('donations.activity.update');
    // hidden delete route (commented)
    // Route::delete('/donations/activity/{aid}/{did}', [App\Http\Controllers\Financial\FinancialDonationController::class, 'destroyDonation'])->name('donations.activity.destroy');

    // Expenses management
    Route::get('/expenses', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/activity/{id}', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'showActivityExpenses'])->name('expenses.activity.show');
    // create either for a specific activity or choose one from list
    Route::get('/expenses/create', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'createExpense'])->name('expenses.create');
    Route::post('/expenses', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'storeExpense'])->name('expenses.store');
    // existing convenience routes that pass activity id directly
    Route::get('/expenses/activity/{id}/create', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'createExpense'])->name('expenses.activity.create');
    Route::post('/expenses/activity/{id}', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'storeExpense'])->name('expenses.activity.store');
    Route::get('/expenses/activity/{aid}/edit/{eid}', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'editExpense'])->name('expenses.activity.edit');
    Route::put('/expenses/activity/{aid}/edit/{eid}', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'updateExpense'])->name('expenses.activity.update');
    Route::delete('/expenses/activity/{aid}/{eid}/receipt', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'deleteReceipt'])->name('expenses.activity.delete-receipt');
    // delete route commented
    // Route::delete('/expenses/activity/{aid}/{eid}', [App\Http\Controllers\Financial\FinancialExpenseController::class, 'destroyExpense'])->name('expenses.activity.destroy');

    // Reports
    Route::get('/reports', [App\Http\Controllers\Financial\FinancialReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/activities', [App\Http\Controllers\Financial\FinancialReportController::class, 'activitiesReport'])->name('reports.activities');
    Route::get('/reports/donations', [App\Http\Controllers\Financial\FinancialReportController::class, 'donationsReport'])->name('reports.donations');
    Route::get('/reports/expenses', [App\Http\Controllers\Financial\FinancialReportController::class, 'expensesReport'])->name('reports.expenses');

    Route::post('/logout', [App\Http\Controllers\Auth\UnifiedAuthController::class, 'logout'])->name('logout');
});
