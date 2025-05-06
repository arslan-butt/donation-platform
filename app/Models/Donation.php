<?php

namespace App\Models;

use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use HasAuthor;

    protected $fillable = [
        'donor_id',
        'campaign_id',
        'payment_method_id',
        'amount',
        'payment_reference',
        'status',
        'donated_at',
    ];

    // Casts
    protected $casts = [
        'donated_at' => 'datetime',
    ];

    // Relationships
    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
