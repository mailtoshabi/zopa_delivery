<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['paid_at'=>'date'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


}
