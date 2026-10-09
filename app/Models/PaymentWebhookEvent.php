<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentWebhookEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'event_type',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }
}