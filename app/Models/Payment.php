<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'transaction_reference',
        'card_brand',
        'card_last4',
        'response_message',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
