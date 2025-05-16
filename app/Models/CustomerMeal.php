<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeal extends Model
{
    use HasFactory;

    protected $table = 'customer_meal';

    protected $fillable = [
        'order_id',
        'meal_id',
        'price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'order_id');  // Ensuring 'order_id' is the foreign key
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
