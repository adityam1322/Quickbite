<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPartnerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<DeliveryAssignment, $this> */
    public function deliveryAssignments(): HasMany
    {
        return $this->hasMany(DeliveryAssignment::class, 'delivery_partner_profile_id');
    }
}
