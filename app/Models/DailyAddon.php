<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAddon extends Model
{

    protected $casts = [
        'is_auto' => 'boolean',
        'is_delivered' => 'boolean',
    ];

    protected $fillable = [
        'daily_meal_id', 'addon_id', 'quantity', 'is_delivered', 'is_auto'
    ];

    public function dailyMeal()
    {
        return $this->belongsTo(DailyMeal::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
