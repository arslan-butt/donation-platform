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
        'paid_at' => 'datetime', // Ensure paid_at is cast to a datetime object
    ];

    // Define relationships
    public function donation()
    {
        return $this->belongsTo(Donation::class); // Each payment belongs to a donation
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class); // Each payment uses a payment method
    }
}
