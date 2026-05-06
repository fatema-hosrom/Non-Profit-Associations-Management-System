<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Supervisor\ManagerController;
use App\Http\Middleware\CheckSupervisorAuth;
use App\Http\Controllers\Supervisor\SupervisorProfileController;
use App\Http\Controllers\Supervisor\OrganizationActivityController;
use App\Http\Controllers\Supervisor\SupervisorVolunteerController;

Route::middleware([CheckSupervisorAuth::class])->prefix('supervisor')->name('supervisor.')->group(function () {
    // Supervisor dashboard
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');

    // Supervisor profile
    Route::get('/profile', [SupervisorProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [SupervisorProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/edit', [SupervisorProfileController::class, 'updateProfile'])->name('profile.update');

    // Manager management
    Route::get('/managers', [ManagerController::class, 'viewManager'])->name('managers.index');
    Route::get('/managers/show/{id}', [ManagerController::class, 'showManager'])->name('managers.show');
    Route::match(['get', 'post'], '/managers/add', [ManagerController::class, 'addManager'])->name('managers.add');
    Route::match(['get', 'post'], '/managers/edit/{id}', [ManagerController::class, 'editManager'])->name('managers.edit');
    Route::delete('/managers/delete/{id}', [ManagerController::class, 'deleteManager'])->name('managers.destroy');
    // Assign roles to managers
    Route::match(['get', 'post'], '/manager/assign-role', [ManagerController::class, 'assignRole'])->name('assignRole');

    // Activity and organization management
    // Manage Sahem activities
    route::get('/activities', [OrganizationActivityController::class, 'getActivities'])->name('activities.index');
    route::get('/activities/view/{id}', [OrganizationActivityController::class, 'ShowActivity'])->name('activities.show');

    // Organization management
    route::get('/organizations', [OrganizationActivityController::class, 'getOrganizations'])->name('organizations.index');
    route::get('/organizations/view/{id}', [OrganizationActivityController::class, 'showOrganization'])->name('organizations.show');
    // Organization events management
    route::get('/organizations/{orgId}/events', [OrganizationActivityController::class, 'getEvents'])->name('organizations.events.index');
    route::get('/organizations/events/view/{id}', [OrganizationActivityController::class, 'viewEvent'])->name('organizations.events.show');

    // Volunteer registration
    route::get('/volunteers', [SupervisorVolunteerController::class, 'index'])->name('volunteers.index');
    route::get('/volunteers/{id}', [SupervisorVolunteerController::class, 'show'])->name('volunteers.show');
    route::patch('/volunteers/{id}/status', [SupervisorVolunteerController::class, 'updateStatus'])->name('volunteers.updateStatus');
});
