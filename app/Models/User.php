<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Business;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'avatar',
    'locale',
    'timezone',
    'default_business_id',
    'last_login_at',
    'is_active',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_users')
                    ->withPivot('role', 'permissions', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Check if the user is an admin/owner of the given business.
     * Defaults to the user's default business.
     */
    public function isAdmin(?int $businessId = null): bool
    {
        $businessId = $businessId ?? $this->default_business_id;

        if (!$businessId) {
            return false;
        }

        return $this->businesses()
            ->wherePivot('business_id', $businessId)
            ->wherePivotIn('role', ['owner', 'admin'])
            ->exists();
    }
}
