<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryPartnerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
