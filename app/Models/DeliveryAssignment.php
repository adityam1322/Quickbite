<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAssignment extends Model
{
    use HasFactory;

    protected $table = 'orders_delivery_assignments';

    protected $fillable = [
        'order_id',
        'delivery_partner_profile_id',
        'assigned_at',
        'picked_up_at',
        'status',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<DeliveryPartnerProfile, $this> */
    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(DeliveryPartnerProfile::class, 'delivery_partner_profile_id');
    }
}
