<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationActivity;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'description',
        'amount',
        'expense_date',
        'receipt',
        'receipt_number',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class);
    }
}
