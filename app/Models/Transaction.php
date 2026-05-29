<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
   protected $fillable = [
    'invoice_number', 
    'customer_name', 
    'subtotal',
    'discount',
    'discount_percent',
    'tax',
    'total_amount', 
    'paid_amount', 
    'change_amount', 
    'payment_method', 
    'status', 
    'user_id', 
    'notes'
];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];
    
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function member()
    {
    return $this->belongsTo(Member::class);
    }
}