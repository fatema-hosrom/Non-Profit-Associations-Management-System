<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Volunteer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'age',
        'nationality',
        'address',
        'skills',
        'experience',
        'education_level',
        'availability',
        'preferred_roles',
        'languages',
        'emergency_contact',
        'status',
    ];

    protected $hidden = ['password'];

    /**
     * Volunteer assignment requests relationship
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ActivityVolunteerAssignment::class, 'volunteer_id');
    }

    /**
     * Activities the volunteer has applied to
     */
    public function activities()
    {
        return $this->belongsToMany(
            OrganizationActivity::class,
            'activity_volunteer_assignments',
            'volunteer_id',
            'activity_id'
        )->withPivot('status', 'request_date', 'decision_date', 'joined_at', 'rejection_reason');
    }

    /**
     * Useful scopes
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
