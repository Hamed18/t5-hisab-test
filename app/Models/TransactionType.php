<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $fillable = ['slug', 'label', 'effect', 'transfer', 'is_active'];

    protected $casts = [
        'transfer' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Scope to return only active types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
