<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerAuthController;
use App\Http\Controllers\VolunteerDashboardController;
use App\Http\Middleware\VolunteerAuth;

/**
 * Login and registration routes for volunteers
 */
Route::prefix('volunteer')->name('volunteer.')->group(function () {
    // Login
    Route::get('/login', [VolunteerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [VolunteerAuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [VolunteerAuthController::class, 'logout'])->name('logout');

    // Dashboard (protected by session)
    Route::middleware([VolunteerAuth::class])->group(function () {
        Route::get('/dashboard', [VolunteerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [VolunteerDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [VolunteerDashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/available-activities', [VolunteerDashboardController::class, 'availableActivities'])
            ->name('available-activities');
        Route::post('/request-volunteer/{activityId}', [VolunteerDashboardController::class, 'requestVolunteer'])
            ->name('request-volunteer');

        Route::get('/my-requests', [VolunteerDashboardController::class, 'myRequests'])->name('my-requests');
        Route::get('/past-activities', [VolunteerDashboardController::class, 'pastActivities'])->name('past-activities');

        // Verify check-in via code
        Route::post('/activity/{activityId}/check-in', [VolunteerDashboardController::class, 'checkIn'])->name('check-in');
    });
});
