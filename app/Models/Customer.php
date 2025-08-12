<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Customer extends Authenticatable
{

    use Notifiable;

    protected $guard = 'customer';

    const DIR_STORAGE = 'storage/customers/';
    const DIR_PUBLIC = 'customers';

    protected $hidden = [
        'id',
        'user_id',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_approved' => 'boolean',
        'datetime' => 'date',
    ];

    protected $fillable = [
        'name',
        'phone',
        'password',
        'office_name',
        'designation',
        'city',
        'landmark',
        'designation',
        'district_id',
        'state_id',
        'postal_code',
        'image_filename',
        'whatsapp',
        'type',
        'daily_quantity',
        'kitchen_id',
        'status',
        'is_approved',
        'latitude',
        'longitude',
        'location_name',
        'firebase_uid',
        'language',
        'user_id',
    ];

    // If passwords are hashed
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'customer_meal')
                    ->withPivot('price', 'quantity', 'pay_method', 'is_paid', 'status')
                    ->withTimestamps();
    }

    public function mealWallet()
    {
        return $this->hasOne(MealWallet::class);
    }

    public function dailyMeals()
    {
        return $this->hasMany(DailyMeal::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'customer_addon')
                    ->withPivot('price', 'quantity', 'pay_method', 'is_paid', 'status')
                    ->withTimestamps();
    }

    public function addonWallets()
    {
        return $this->hasMany(AddonWallet::class, 'customer_id', 'id');
    }

    public function mealLeaves()
    {
        return $this->hasMany(MealLeave::class);
    }

    public function deleteImage(){
        if ($this->image_filename && Storage::exists(self::DIR_PUBLIC . '/' . $this->image_filename)) {
            Storage::delete(self::DIR_PUBLIC . '/' . $this->image_filename);
        }
    }

    public function dailyQuantityOverrides()
    {
        return $this->hasMany(DailyQuantityOverride::class);
    }

    public function getTodayQuantity()
    {
        $override = $this->dailyQuantityOverrides()->where('date', today())->first();
        return $override ? $override->quantity : $this->daily_quantity;
    }

    public function getCutoffTimeAttribute()
    {
        // Use kitchen-specific cutoff time if available, else default constant
        $time = $this->kitchen?->cutoff_time ?? Utility::CUTOFF_TIME;

        // Ensure it returns in H:i format
        return $time ? Carbon::createFromFormat('H:i:s', $time)->format('H:i') : null;
    }

}
