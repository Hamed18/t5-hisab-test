<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'type', 'parent_id', 'business_id',
        'description', 'is_system', 'is_active', 'display_order',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['income', 'both']);
    }

    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['expense', 'both']);
    }

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}