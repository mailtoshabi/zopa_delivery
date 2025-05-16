<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $fillable = ['name','code','gst_code','type'] ;

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

}
