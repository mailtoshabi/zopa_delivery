<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'customer_id',
        'message',
        'reply',
        'status',
        'replied_at',
        'is_public',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    // Relationship: Feedback belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Scope: Public feedback only
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
