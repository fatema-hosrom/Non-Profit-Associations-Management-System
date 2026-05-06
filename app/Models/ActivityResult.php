<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityResult extends Model
{
    protected $fillable = [
        'activity_id',
        'total_volunteers',
        'total_hours',
        'attendance_count',
        'goals_achieved',
        'challenges',
        'notes',
        'images',
        'report_file',
        'created_by',
    ];

    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class, 'activity_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'created_by');
    }
}
