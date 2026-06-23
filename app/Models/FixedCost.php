<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FixedCost extends Model
{
    use LogsActivity;

    protected $fillable = [
        'business_id', 'item', 'type', 'frequency',
        'amount', 'currency', 'bdt_amount',
        'effective_from', 'effective_to', 'ask_day',
        'status', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    // Relationship
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Scopes
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}
