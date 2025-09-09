<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'notes',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
    /**
     * Relation: Transaction belongs to a Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
