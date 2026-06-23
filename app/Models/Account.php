<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Account extends Model
{
    use LogsActivity;

    protected $fillable = [
    'name',
    'type',
    'opening_balance',
    'account_number',
    'bank_name',
    'branch_name',
    'is_active',
];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_business' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Scope to active accounts
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
