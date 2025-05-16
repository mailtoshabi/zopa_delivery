<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kitchen extends Model
{
    /** @use HasFactory<\Database\Factories\KitchenFactory> */
    use HasFactory;
    const DIR_STORAGE = 'storage/kitchens/';
    const DIR_PUBLIC = 'kitchens';

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean'];

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

    public function deleteImage(){
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

}
