<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function kitchens()
    {
        return $this->belongsToMany(Kitchen::class, 'kitchen_addon')
                    ->withPivot('price', 'image_filename', 'status')
                    ->withTimestamps();
    }

    public static function withKitchenOverrides($addonId = null)
    {
        $customer = Auth::guard('customer')->user();

        $query = self::query()
            ->select([
                'addons.*',
                DB::raw("COALESCE(kitchen_addon.price, addons.price) AS price"),
                DB::raw("COALESCE(kitchen_addon.status, addons.status) AS status"),
                DB::raw("COALESCE(kitchen_addon.image_filename, addons.image_filename) AS image_filename"),
            ])
            ->leftJoin('kitchen_addon', function ($join) use ($customer) {
                $join->on('addons.id', '=', 'kitchen_addon.addon_id')
                    ->where('kitchen_addon.kitchen_id', '=', $customer->kitchen_id);
            });

        if ($addonId) {
            $query->where('addons.id', $addonId);
        }
        // ✅ Only return if effective status = active
        $query->having('status', '=', Utility::ITEM_ACTIVE);

        return $query;
    }

    public function deleteImage(): void
    {
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

}
