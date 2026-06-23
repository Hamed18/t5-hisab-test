<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    protected $fillable = [
        'owner_user_id', 'name', 'slug', 'type', 'logo', 'description',
        'currency', 'fiscal_year_start', 'address', 'phone', 'email',
        'website', 'tax_id', 'registration_no', 'settings', 'modules_enabled',
        'is_active', 'archived_at', 'branch', 'is_primary',
    ];

    protected $casts = [
        'settings' => 'array',
        'modules_enabled' => 'array',
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'archived_at' => 'datetime',
    ];

    protected $attributes = [
        'modules_enabled' => '["transactions", "wallet", "reports"]',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
                    ->withPivot('role', 'permissions', 'is_active')
                    ->withTimestamps();
    }
}
