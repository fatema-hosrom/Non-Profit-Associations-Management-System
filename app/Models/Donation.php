<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationActivity;
use App\Models\Donor;
use App\Models\Manager;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'activity_id',
        'amount',
        'donation_type',
        'date',
        'notes',
        'created_by',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'date' => 'datetime',
        'deleted_at' => 'datetime',
        'amount' => 'decimal:2',
        'is_deleted' => 'boolean',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class);
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(Manager::class, 'deleted_by');
    }

    public function corrections()
    {
        return $this->hasMany(DonationCorrection::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
