<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityVolunteerAssignment extends Model
{
    protected $fillable = [
        'activity_id',
        'volunteer_id',
        'status',
        'checkin_code',
        'checked_in_at',
        'joined_at',
        'request_date',
        'decision_date',
        'rejection_reason',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'request_date' => 'datetime',
        'decision_date' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            if (empty($assignment->checkin_code)) {
                $assignment->checkin_code = static::generateUniqueCheckinCode();
            }
        });
    }

    /**
     * Verification code consisting of 6 characters (uppercase English letters + digits), unique in the table.
     */
    public static function generateUniqueCheckinCode(): string
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('checkin_code', $code)->exists());

        return $code;
    }

    // Relationships
    public function activity(): BelongsTo
    {
        return $this->belongsTo(OrganizationActivity::class, 'activity_id');
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    // Useful scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeRemoved($query)
    {
        return $query->where('status', 'removed');
    }

    public function scopeByActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeByVolunteer($query, $volunteerId)
    {
        return $query->where('volunteer_id', $volunteerId);
    }
}
