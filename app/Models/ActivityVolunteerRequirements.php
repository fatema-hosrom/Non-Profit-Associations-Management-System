<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityVolunteerRequirements extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'activity_id',
        'required_volunteers',
        'volunteers_count',
        'volunteer_mode',
        'min_age',
        'gender_requirement',
        'skills_required',
        'min_hours'
    ];

    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class, 'activity_id');
    }
}
