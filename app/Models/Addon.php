<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Addon extends Model
{
    use HasFactory;
    const DIR_PUBLIC = 'addons';

    protected $hidden = ['id'];


    protected $fillable = [
        'name',
        'price',
        'description',
        'image_filename',
        'additional_images',
        'order',
        'status',
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

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_addon')
                    ->withPivot('invoice_no','price','quantity','pay_method','is_paid','status')
                    ->withTimestamps();
    }

    public function deleteImage(): void
    {
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

}
