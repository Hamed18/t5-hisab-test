<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class CurrencyRate extends Model
{
    use LogsActivity;

    protected $table = 'currency_rates';

    protected $fillable = [
        'business_id', 'currency', 'rate_to_bdt', 'effective_from',
        'effective_to', 'status', 'source', 'changed_by_user_id',
        'previous_rate', 'change_percent', 'notes',
    ];

    protected $casts = [
        'rate_to_bdt' => 'decimal:4',
        'previous_rate' => 'decimal:4',
        'change_percent' => 'decimal:4',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    // Scope for active rates
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get the rate effective on a specific date
    public function scopeEffectiveOn($query, $date)
    {
        return $query->where('effective_from', '<=', $date)
                     ->where(function ($q) use ($date) {
                         $q->whereNull('effective_to')
                           ->orWhere('effective_to', '>=', $date);
                     });
    }
}
