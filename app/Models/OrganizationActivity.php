<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationActivity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'activity_type',
        'location',
        'start_date',
        'end_date',
        'image',
        'status',
        'is_published',
        'manager_id',
        'approved_by'
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function donationSettings()
    {
        return $this->hasOne(ActivityDonationSettings::class, 'activity_id');
    }

    public function volunteerRequirements()
    {
        return $this->hasOne(ActivityVolunteerRequirements::class, 'activity_id');
    }

    public function assignments()
    {
        return $this->hasMany(ActivityVolunteerAssignment::class, 'activity_id');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'activity_id');
    }

    public function results()
    {
        return $this->hasOne(ActivityResult::class, 'activity_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'activity_id');
    }
}
