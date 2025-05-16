<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'leave_at',
    ];

    protected $dates = ['leave_at'];

    /**
     * Get the customer who marked the leave.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
