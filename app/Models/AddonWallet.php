<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonWallet extends Model
{
    use HasFactory;

    protected $table = 'addon_wallet';

    protected $fillable = [
        'customer_id',
        'addon_id',
        'quantity',
        'status',
    ];

    /**
     * Get the customer that owns the meal addon_wallet.
     */
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function addon() {
        return $this->belongsTo(Addon::class);
    }
}
