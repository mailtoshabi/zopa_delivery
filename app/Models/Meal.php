<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Meal extends Model
{
    use HasFactory;
    const DIR_PUBLIC = 'meals';

    protected $hidden = ['id'];


    protected $fillable = [
        'category_id',
        'mess_category_id',
        'wallet_group_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mess_category()
    {
        return $this->belongsTo(MessCategory::class);
    }

    public function walletGroup()
    {
        return $this->belongsTo(WalletGroup::class, 'wallet_group_id');
    }

    public function kitchens()
    {
        return $this->belongsToMany(Kitchen::class, 'kitchen_meal')
                    ->withPivot('price', 'image_filename', 'status')
                    ->withTimestamps();
    }

    public static function withKitchenOverrides($mealId = null)
    {
        $customer = Auth::guard('customer')->user();

        $query = self::query()
            ->select([
                'meals.*',
                DB::raw("COALESCE(kitchen_meal.price, meals.price) AS price"),
                DB::raw("COALESCE(kitchen_meal.status, meals.status) AS status"),
                DB::raw("COALESCE(kitchen_meal.image_filename, meals.image_filename) AS image_filename"),
            ])
            ->leftJoin('kitchen_meal', function ($join) use ($customer) {
                $join->on('meals.id', '=', 'kitchen_meal.meal_id')
                    ->where('kitchen_meal.kitchen_id', '=', $customer->kitchen_id);
            });

        if ($mealId) {
            $query->where('meals.id', $mealId);
        }

        // ✅ Only return if effective status = active
        $query->having('status', '=', Utility::ITEM_ACTIVE);

        return $query;
    }


}
