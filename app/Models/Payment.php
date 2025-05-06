<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'donation_id',
        'payment_method_id',
        'gateway_transaction_id',
        'amount',
        'fee_amount',
        'raw_response',
        'status',
        'paid_at',
    ];

    // Define the casts for attributes
    protected $casts = [
        'raw_response' => 'array', // Cast raw_response to an array
        'paid_at' => 'datetime',
    ];

    // Define relationships
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
