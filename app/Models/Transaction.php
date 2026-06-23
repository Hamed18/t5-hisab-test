<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Transaction extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'business_id', 'location_id', 'date', 'type', 'category_id', 'category_type',
        'description', 'amount', 'currency', 'exchange_rate', 'bdt_amount',
        'account_id', 'related_account_id', 'contact_id', 'reference_type',
        'reference_id', 'receipt_path', 'receipt_id', 'has_receipt',
        'notes', 'tags', 'added_by_user_id', 'approved_by_user_id',
        'approved_at', 'status', 'is_recurring', 'parent_recurring_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'tags' => 'array',
        'is_recurring' => 'boolean',
        'has_receipt' => 'boolean',
    ];

    public function business(): BelongsTo { return $this->belongsTo(Business::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function account(): BelongsTo { return $this->belongsTo(Account::class); }
    public function relatedAccount(): BelongsTo { return $this->belongsTo(Account::class, 'related_account_id'); }
    public function addedBy(): BelongsTo { return $this->belongsTo(User::class, 'added_by_user_id'); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by_user_id'); }
    
    public function reference(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    protected static function booted(): void
    {
        static::created(function (Transaction $transaction) {
            $transaction->adjustBalances(false);
        });

        static::updated(function (Transaction $transaction) {
            $original = new Transaction($transaction->getOriginal());
            $original->adjustBalances(true);
            $transaction->adjustBalances(false);
        });

        static::deleted(function (Transaction $transaction) {
            $transaction->adjustBalances(true);
        });
    }

    public function adjustBalances(bool $reverse = false): void
    {
         $type = TransactionType::where('slug', $this->type)->first();
         if (!$type) return;

         $amount = $this->amount;
         if ($amount <= 0) return;

         $effect = $type->effect;
         if ($reverse) {
             $effect = $effect === 'add' ? 'subtract' : 'add';
         }

         $account = Account::find($this->account_id);
         if ($account) {
             $effect === 'add'
                 ? $account->increment('current_balance', $amount)
                 : $account->decrement('current_balance', $amount);
         }

         if ($type->transfer && $this->related_account_id) {
             $relatedAccount = Account::find($this->related_account_id);
             if ($relatedAccount) {
                 $transferEffect = $effect === 'add' ? 'subtract' : 'add';
                 $transferEffect === 'add'
                     ? $relatedAccount->increment('current_balance', $amount)
                     : $relatedAccount->decrement('current_balance', $amount);
             }
         }
    }
}