<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\Auth\UnifiedAuthController;

require __DIR__ . '/manager/manager_web.php';
require __DIR__ . '/supervisor/supervisor_web.php';
require __DIR__ . '/financial/financial_web.php';
require __DIR__ . '/volunteer/volunteer_web.php';

/*|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------*/
route::prefix('/sahem')->name('public.')->group(function () {
    // Home page
    Route::get('/home', [PublicController::class, 'home'])->name('home');
    // Organizations
    Route::get('/organizations', [PublicController::class, 'organizations'])->name('organizations.index');

    // Specific organization page
    Route::get('/organizations/{id}', [PublicController::class, 'showOrganization'])->name('organizations.show');

    /*
|--------------------------------------------------------------------------
| Activities
|--------------------------------------------------------------------------
*/

    // Sahem activities
    Route::get('/activities', [PublicController::class, 'sahemActivities'])
        ->name('activities.index');
    // Display specific Sahem activity details
    Route::get('/activities/sahem/{id}', [PublicController::class, 'showSahemActivity'])
        ->name('activities.sahem.show');

    // Completed activities
    Route::get('/completed-activities', [PublicController::class, 'completedActivities'])
        ->name('completed-activities');
    Route::get('/completed-activities/{id}', [PublicController::class, 'showCompletedActivity'])
        ->name('completed-activities.show');

    // Organization events
    Route::get('/organization', [PublicController::class, 'organizationEvents'])
        ->name('organization.events_index');
    // Display specific organization event details
    Route::get('/organization/{id}', [PublicController::class, 'showOrganizationEvent'])
        ->name('organization.event_show');

    // Handle donation payment by card
    Route::post('/activities/sahem/{id}/payment', [PaymentController::class, 'processPayment'])
        ->name('activities.payment.process');
    Route::get('/payments/{paymentId}/receipt', [PaymentController::class, 'downloadReceipt'])
        ->name('activities.payment.receipt');

    // Register new volunteer
    Route::get('/volunteer/register', [VolunteerController::class, 'volunteerRegister'])->name('volunteer.register');
    Route::post('/volunteer/register', [VolunteerController::class, 'volunteerStore'])->name('volunteer.store');
});

/*|--------------------------------------------------------------------------
| Unified Authentication Routes
|--------------------------------------------------------------------------*/
Route::get('/login', [UnifiedAuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [UnifiedAuthController::class, 'login'])->name('auth.login.post');
// Admin logout
Route::get('/logout/manager', [UnifiedAuthController::class, 'logoutManager'])->name('logout.manager');

Route::get('/logout/financial', [UnifiedAuthController::class, 'logoutFinancial'])->name('logout.financial');

Route::get('/logout/supervisor', [UnifiedAuthController::class, 'logoutSupervisor'])->name('logout.supervisor');

