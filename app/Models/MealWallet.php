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
        'wallet_group_id',
        'quantity',
        'status',
        'is_on',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_on' => 'boolean',
    ];

    /**
     * Get the customer that owns the meal meal_wallet.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function walletGroup()
    {
        return $this->belongsTo(WalletGroup::class, 'wallet_group_id');
    }
}
