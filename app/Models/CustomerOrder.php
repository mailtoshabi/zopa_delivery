<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'amount',
        'discount',
        'delivery_charge',
        'is_paid',
        'status',
        'notes',
        'ip_address',
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

    public function calculateTotal()
    {
        // Sum meal prices directly (each meal counted once)
        $mealTotal = $this->meals()->sum('price');

        // Addon total still depends on quantity
        $addonTotal = $this->addons()->sum(DB::raw('price * quantity'));

        $total = $mealTotal + $addonTotal;

        // Apply discount if any
        if ($this->discount) {
            $total -= $this->discount;
        }

        // Add delivery charge if any
        if ($this->delivery_charge) {
            $total += $this->delivery_charge;
        }

        return max($total, 0); // prevent negative total
    }

    /**
     * Define the relationship with the Meal model.
     */
}
