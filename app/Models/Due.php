<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Due extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'dues';

    protected $fillable = [
        'business_id', 'contact_id', 'invoice_number', 'description',
        'total_amount', 'paid_amount', 'currency', 'type',
        'due_date', 'last_payment_date', 'last_payment_amount',
        'status', 'priority', 'notes', 'follow_up',
        'reminder_count', 'reminder_sent_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'last_payment_amount' => 'decimal:2',
        'due_date' => 'date',
        'last_payment_date' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    // Accessor for remaining (computed via MySQL virtual column)
    // But we also provide a dynamic calculation in case the DB doesn't support stored virtual columns.
    public function getRemainingAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // Scopes
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['paid', 'written_off']);
    }

    // Boot: auto-update status if due date passed and not paid
    protected static function booted()
    {
        static::saving(function ($due) {
            // If paid amount equals total, mark as paid
            if ($due->paid_amount >= $due->total_amount) {
                $due->status = 'paid';
            } elseif ($due->paid_amount > 0) {
                $due->status = 'partial';
            } elseif ($due->due_date < now() && $due->status != 'written_off') {
                // Only auto‑set overdue if status isn't already manually changed to something else
                if (!in_array($due->status, ['paid', 'written_off', 'overdue'])) {
                    $due->status = 'overdue';
                }
            } else {
                $due->status = 'pending';
            }
        });
    }
}
