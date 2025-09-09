<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'opening_balance',
    ];
    
    protected $casts = [
        'opening_balance' => 'decimal:2',
    ];

    /**
     * Relation: Customer belongs to a User (Shopkeeper)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation: Customer has many Transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Total credits (numeric)
     */
    public function getTotalCreditsAttribute(): float
    {
        // Using the relationship collection if loaded avoids extra queries
        if ($this->relationLoaded('transactions')) {
            return (float) $this->transactions->where('type', 'credit')->sum('amount');
        }
        return (float) $this->transactions()->where('type', 'credit')->sum('amount');
    }

    /**
     * Total debits (numeric)
     */
    public function getTotalDebitsAttribute(): float
    {
        if ($this->relationLoaded('transactions')) {
            return (float) $this->transactions->where('type', 'debit')->sum('amount');
        }
        return (float) $this->transactions()->where('type', 'debit')->sum('amount');
    }

    /**
     * Final balance (numeric)
     * Formula: Total Credits - Total Debits
     */
    public function getBalanceAttribute(): string
    {
        $credits = (double) $this->total_credits;
        $debits  = (double) $this->total_debits;

        return number_format(($credits - $debits), 2, '.', '');
    }

    public function addOpeningBalance(float $amount)
    {
        if ($amount <= 0) {
            return null;
        }

        return $this->transactions()->create([
            'type'   => 'credit',
            'amount' => $amount,
            'notes'  => 'Opening balance',
            'date'   => now()->toDateString(),
        ]);
    }
}
