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
        'file_path',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Accessor to automatically prepend base URL
    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : asset('imgs/preview.png');
    }


    /**
     * Relation: Transaction belongs to a Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
