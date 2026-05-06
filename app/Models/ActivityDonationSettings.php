<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityDonationSettings extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'activity_id',
        'target_amount',
        'collected_amount',
        'donation_status'
    ];

    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class, 'activity_id');
    }
}
