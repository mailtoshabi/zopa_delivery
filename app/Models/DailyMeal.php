<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{

    protected $casts = [
        'status' => 'boolean',
        'is_auto' => 'boolean',
        'is_delivered' => 'boolean',
        'date' => 'date',
    ];

    protected $fillable = [
        'customer_id', 'quantity', 'is_delivered', 'date', 'status', 'is_auto', 'reason'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function addons()
    {
        return $this->hasMany(DailyAddon::class);
    }

    public function dailyAddons()
    {
        return $this->hasMany(DailyAddon::class, 'daily_meal_id');
    }

}
