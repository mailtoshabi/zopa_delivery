<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Kitchen extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\KitchenFactory> */
    use HasFactory;
    const DIR_STORAGE = 'storage/kitchens/';
    const DIR_PUBLIC = 'kitchens';
    const DIR_PUBLIC_LICESNSE = 'kitchens/license';
    const DIR_PUBLIC_FSSAI = 'kitchens/fssai';
    const DIR_PUBLIC_OTHDOC = 'kitchens/documents';

    protected $hidden = ['id','password', 'remember_token'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean','other_documents'=>'array'];

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'kitchen_meal')
                    ->withPivot('price', 'image_filename', 'status')
                    ->withTimestamps();
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'kitchen_addon')
                    ->withPivot('price', 'image_filename', 'status')
                    ->withTimestamps();
    }

    public function deleteImage(){
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

}
