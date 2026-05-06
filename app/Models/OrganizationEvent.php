<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationEvent extends Model
{
    protected $table = 'organization_events';

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'image',
        'external_url',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The organization this event belongs to
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * The manager who created the event (nullable)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'created_by');
    }
}
