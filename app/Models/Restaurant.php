<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'phone',
        'email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function Hours(): HasMany{
         return $this->hasMany(RestaurantHours::class);
    }

    public function cuisines(): BelongsToMany{
        return $this->belongsToMany(Cuisine::class);
    }

    public function Servicearea(): HasMany{
        return $this->HasMany(RestaurantServicearea::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(RestaurantStaff::class);
    }


}