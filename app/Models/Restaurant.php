<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

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

    /** @return HasMany<RestaurantHours, $this> */
    public function hours(): HasMany
    {
        return $this->hasMany(RestaurantHours::class);
    }

    /** @return BelongsToMany<Cuisine, $this> */
    public function cuisines(): BelongsToMany
    {
        return $this->belongsToMany(Cuisine::class);
    }

    /** @return HasMany<RestaurantServiceArea, $this> */
    public function serviceAreas(): HasMany
    {
        return $this->hasMany(RestaurantServiceArea::class);
    }

    /** @return HasMany<RestaurantStaff, $this> */
    public function staff(): HasMany
    {
        return $this->hasMany(RestaurantStaff::class);
    }

    /** @return HasMany<MenuCategorie, $this> */
    public function categories(): HasMany
    {
        return $this->hasMany(MenuCategorie::class);
    }

    /** @return HasMany<Cart, $this> */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /** @return HasMany<Order, $this> */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
