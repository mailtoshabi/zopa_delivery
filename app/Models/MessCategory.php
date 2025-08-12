<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessCategory extends Model
{
    use HasFactory;
    use LogsActivity;

    const DIR_STORAGE = 'storage/mess_categories/';
    const DIR_PUBLIC = 'mess_categories/';

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'name'])
        ->setDescriptionForEvent(fn(string $eventName) => "The Mess Category has been {$eventName}");
    }

    public function scopeActive($query) {
        return $query->where('status',Utility::ITEM_ACTIVE);
    }

    public function scopeOldestById($query) {
        return $query->orderBy('id', 'asc');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public static function withActiveMealsForKitchen()
    {
        $customer = Auth::guard('customer')->user();

        return self::whereHas('meals', function ($q) use ($customer) {
                $q->select([
                        'meals.*',
                        DB::raw("COALESCE(kitchen_meal.status, meals.status) AS status")
                    ])
                    ->leftJoin('kitchen_meal', function ($join) use ($customer) {
                        $join->on('meals.id', '=', 'kitchen_meal.meal_id')
                            ->where('kitchen_meal.kitchen_id', '=', $customer->kitchen_id);
                    })
                    ->where('meals.status', Utility::ITEM_ACTIVE); // ✅ only base meal active
            })
            ->orderBy('display_order','asc')
            ->get();
    }

}
