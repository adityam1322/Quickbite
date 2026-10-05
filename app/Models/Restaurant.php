<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function Categorie(): HasMany{
        return $this->HasMany(MenuCategorie::class);
    }
    
    public function cart(): HasMany{
        return $this->HasMany(Cart::class);
    }

    public function order(): HasMany{
        return $this->hasMany(Order::class);
    }


}