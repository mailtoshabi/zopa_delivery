<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuantityOverride extends Model
{
    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = ['customer_id', 'date', 'quantity'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
