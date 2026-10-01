<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantStaff extends Model
{
    protected $table = 'restaurant_staff';

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}