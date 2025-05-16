<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Branch extends Authenticatable
{
    use HasFactory;

    const DIR_STORAGE = 'storage/branches/';
    const DIR_PUBLIC = 'branches/';

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    // public function sales() {
    //     return $this->hasMany(Sale::class);
    // }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
