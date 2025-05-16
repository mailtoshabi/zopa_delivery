<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'status', 'description','user_id'];

    public function meals(){
        return $this->belongsToMany(Meal::class);
    }
}
