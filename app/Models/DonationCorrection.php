<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'reason',
        'corrected_amount',
        'correction_date',
    ];

    protected $casts = [
        'correction_date' => 'datetime',
        'corrected_amount' => 'decimal:2',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
