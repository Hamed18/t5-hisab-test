<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Loan extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'business_id', 'type', 'person', 'date', 'amount', 'currency',
        'bdt_amount', 'purpose', 'due_date', 'repaid_amount', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'repaid_amount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}
