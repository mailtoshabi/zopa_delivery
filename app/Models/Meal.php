<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Meal extends Model
{
    use HasFactory;
    const DIR_PUBLIC = 'meals';

    protected $hidden = ['id'];


    protected $fillable = [
        'name',
        'price',
        'quantity',
        'image_filename',
        'additional_images',
        'order',
        'status',
        'ingredient_ids',
        'remark_ids',
        'user_id',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'status' => 'boolean',
    ];

    public function scopeActive($query) {
        return $query->where('status',Utility::ITEM_ACTIVE);
    }

    public function scopeOldestById($query) {
        return $query->orderBy('id', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }

    public function remarks(){
        return $this->belongsToMany(Remark::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_meal')
                    ->withPivot('invoice_no','customer_id', 'meal_id','price','quantity','pay_method','is_paid','status')
                    ->withTimestamps();
    }

    public function deleteImage(): void
    {
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

}
