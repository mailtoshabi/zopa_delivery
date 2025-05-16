<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealWallet extends Model
{
    use HasFactory;

    protected $table = 'meal_wallet';

    protected $fillable = [
        'customer_id',
        'quantity',
        'status',
    ];

    /**
     * Get the customer that owns the meal meal_wallet.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
