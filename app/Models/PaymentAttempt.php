<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'attempt_number',
        'transaction_id',
        'amount',
        'status',
        'responce_massage',
        'attempted-at',
        'idempotency_key',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'amount' => 'decimal:2',
        'attempted-at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
