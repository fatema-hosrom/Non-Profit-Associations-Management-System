<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ActivityController;
use App\Http\Controllers\Manager\OrganizationController;
use App\Http\Middleware\CheckManagerAuth;
use App\Http\Controllers\Manager\ManagerProfileController;

Route::middleware([CheckManagerAuth::class])->prefix('manager')->name('manager.')->group(function () {
    // Manager dashboard
    Route::get('/dashboard', [ActivityController::class, 'dashboard'])->name('dashboard');

    // Manager profile
    Route::get('/profile', [ManagerProfileController::class, 'profile'])->name('profile');
    // Edit manager profile
    Route::get('/profile/edit', [ManagerProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/edit', [ManagerProfileController::class, 'updateProfile'])->name('profile.update');

    // Activity management
    Route::get('/activities', [ActivityController::class, 'getActivities'])->name('activities.index');
    // Display activity details
    Route::get('/activities/view/{id}', [ActivityController::class, 'viewActivity'])->name('activities.view');
    // Add new activities
    Route::get('/activities/add', [ActivityController::class, 'addActivity'])->name('activities.add');
    Route::post('/activities/add', [ActivityController::class, 'storeActivity'])->name('activities.store');
    // Edit activities
    Route::get('/activities/edit/{id}', [ActivityController::class, 'editActivity'])->name('activities.edit');
    Route::put('/activities/edit/{id}', [ActivityController::class, 'updateActivity'])->name('activities.update');
    // Delete activities
    Route::delete('/activities/{id}', [ActivityController::class, 'destroyActivity'])->name('activities.destroy');
    // Publish and unpublish
    Route::post('/activities/toggle-publish/{id}', [ActivityController::class, 'togglePublish'])->name('activities.togglePublish');

    // Change activity status
    Route::post('/activities/toggle-status/{id}', [ActivityController::class, 'toggleStatus'])->name('activities.toggleStatus');
    Route::post('/activities/change-status/{id}', [ActivityController::class, 'changeStatus'])->name('activities.changeStatus');

    // Activity results - separate pages
    // Display results
    Route::get('/activities/{id}/results', [ActivityController::class, 'manageActivityResults'])->name('activities.results.view');
    // Add new results
    Route::get('/activities/{id}/results/add', [ActivityController::class, 'addActivityResults'])->name('activities.results.add');
    // Edit results
    Route::get('/activities/{id}/results/edit', [ActivityController::class, 'editActivityResults'])->name('activities.results.edit');
    // Save new results
    Route::post('/activities/{id}/results', [ActivityController::class, 'storeActivityResults'])->name('activities.results.store');
    // Update results
    Route::put('/activities/{id}/results', [ActivityController::class, 'updateActivityResults'])->name('activities.results.update');
    // Delete all activity results
    Route::delete('/activities/{id}/results', [ActivityController::class, 'destroyActivityResults'])->name('activities.results.destroy');

    // Volunteer management in activities
    // Display list of activities that need volunteers
    Route::get('/activity-volunteers', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'index'])->name('activity_volunteers.index');
    // Display and manage volunteers in specific activity
    Route::get('/activity-volunteers/{activityId}', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'show'])->name('activity_volunteers.manage');
    // Assign volunteer to activity
    Route::post('/activity-volunteers/{activityId}/assign', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'assignVolunteer'])->name('activity_volunteers.assign');
    // Approve volunteer request
    Route::post('/activity-volunteers/{activityId}/{assignmentId}/approve', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'approveVolunteer'])->name('activity_volunteers.approve');
    // Reject volunteer request
    Route::post('/activity-volunteers/{activityId}/{assignmentId}/reject', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'rejectVolunteer'])->name('activity_volunteers.reject');
    // Remove volunteer from activity
    Route::post('/activity-volunteers/{activityId}/{assignmentId}/remove', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'removeVolunteer'])->name('activity_volunteers.remove');
    // Display volunteer details
    Route::get('/activity-volunteers/{activityId}/{assignmentId}/details', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'viewVolunteer'])->name('activity_volunteers.details');
    // Get list of available volunteers to add
    Route::get('/activity-volunteers/{activityId}/available-volunteers', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'getAvailableVolunteers'])->name('activity_volunteers.available');
    // Download PDF of volunteers
    Route::get('/activity-volunteers/{activityId}/download-pdf', [App\Http\Controllers\Manager\ActivityVolunteersController::class, 'downloadVolunteerPDF'])->name('activity_volunteers.pdf');

    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'getOrganizations'])->name('organizations.index');
    // Display organization details
    Route::get('/organizations/view/{id}', [OrganizationController::class, 'viewOrganization'])->name('organizations.show');
    // Add new organizations
    Route::get('/organizations/create', [OrganizationController::class, 'addOrganization'])->name('organizations.add');
    Route::post('/organizations/create', [OrganizationController::class, 'storeOrganization'])->name('organizations.store');
    // تعديل بيانات الجمعية
    Route::get('/organizations/{id}/edit', [OrganizationController::class, 'editOrganization'])->name('organizations.edit');
    Route::put('/organizations/{id}', [OrganizationController::class, 'updateOrganization'])->name('organizations.update');
    // حذف الجمعية
    Route::delete('/organizations/{id}', [OrganizationController::class, 'destroyOrganization'])->name('organizations.destroy');


    // الفعاليات الخاصة بالجمعيات
    Route::get('/organizations/{orgId}/events', [OrganizationController::class, 'getEvents'])->name('organizations.events.index');
    // اضافة فعالية جديدة للجمعية
    Route::get('/organizations/{orgId}/events/create', [OrganizationController::class, 'createEvent'])->name('organizations.events.create');
    Route::post('/organizations/{orgId}/events', [OrganizationController::class, 'storeEvent'])->name('organizations.events.store');
    // عرض تفاصيل الفعالية للجمعية
    Route::get('/events/{id}', [OrganizationController::class, 'viewEvent'])->name('organizations.events.show');
    // تعديل فعالية الجمعية للجمعية
    Route::get('/events/{id}/edit', [OrganizationController::class, 'editEvent'])->name('organizations.events.edit');
    Route::put('/events/{id}', [OrganizationController::class, 'updateEvent'])->name('organizations.events.update');
    // حذف فعالية الجمعية للجمعية
    Route::delete('/events/{id}', [OrganizationController::class, 'destroyEvent'])->name('organizations.events.destroy');

    // إدارة المتطوعين
    Route::get('/volunteers', [App\Http\Controllers\Manager\VolunteerController::class, 'getVolunteers'])->name('volunteers.index');
    Route::get('/volunteers/add', [App\Http\Controllers\Manager\VolunteerController::class, 'addVolunteer'])->name('volunteers.add');
    Route::post('/volunteers/add', [App\Http\Controllers\Manager\VolunteerController::class, 'storeVolunteer'])->name('volunteers.store');
    Route::get('/volunteers/{id}', [App\Http\Controllers\Manager\VolunteerController::class, 'viewVolunteer'])->name('volunteers.show');
    Route::get('/volunteers/{id}/edit', [App\Http\Controllers\Manager\VolunteerController::class, 'editVolunteer'])->name('volunteers.edit');
    Route::put('/volunteers/{id}', [App\Http\Controllers\Manager\VolunteerController::class, 'updateVolunteer'])->name('volunteers.update');
    Route::delete('/volunteers/{id}', [App\Http\Controllers\Manager\VolunteerController::class, 'destroyVolunteer'])->name('volunteers.destroy');
});
