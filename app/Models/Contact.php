<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Contact extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'business_id', 'name', 'type', 'company', 'email', 'phone', 'image',
    ];

    // Scopes
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}
