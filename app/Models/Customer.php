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
     * Accessor: Calculate current balance
     */
    public function getBalanceAttribute()
    {
        $credits = $this->transactions()->where('type', 'credit')->sum('amount');
        $debits  = $this->transactions()->where('type', 'debit')->sum('amount');

        return $this->opening_balance + ($credits - $debits);
    }
}
