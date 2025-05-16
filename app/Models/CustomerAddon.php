<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddon extends Model
{
    use HasFactory;

    protected $table = 'customer_addon';

    protected $fillable = [
        'order_id',
        'addon_id',
        'price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'order_id');
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }
}
