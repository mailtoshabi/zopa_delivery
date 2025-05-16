<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    // Specify the table if it's not following the default plural convention
    protected $table = 'customer_orders';

    // Define which fields are mass-assignable
    protected $fillable = [
        'invoice_no',
        'customer_id',
        'pay_method',
        'discount',
        'delivery_charge',
        'is_paid',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_paid' => 'boolean',
    ];

    /**
     * Define the relationship with the Customer model.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function meals()
    {
        return $this->hasMany(CustomerMeal::class, 'order_id');
    }

    public function addons()
    {
        return $this->hasMany(CustomerAddon::class, 'order_id');
    }

    /**
     * Define the relationship with the Meal model.
     */
}
