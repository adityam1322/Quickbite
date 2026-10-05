<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class MenuCategorie extends Model
{
    use HasFactory;
    protected $fillable =[
        'restaurant_id',
        'name',
        'slug',
        'description',
        'is_active',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant():belongsTo{
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems(): hasMany{
        return $this->hasMany(MenuItem::class);
    }

}
