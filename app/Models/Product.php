<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory;
    use LogsActivity;

    const DIR_STORAGE = 'storage/products/';
    const DIR_PUBLIC = 'products/';

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean', 'is_approved'=>'boolean', 'images' => 'array'];

    /**
     * Boot the model.
     */

     public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'name'])
        ->setDescriptionForEvent(fn(string $eventName) => "The Product has been {$eventName}");
    }

    public function scopeActive($query) {
        return $query->where('status',Utility::ITEM_ACTIVE);
    }

    public function scopeOldestById($query) {
        return $query->orderBy('id', 'asc');
    }

    // public function ingredients()
    // {
    //     return $this->hasMany(Component::class);
    // }

    public function ingredients()
    {
        return $this->belongsToMany(Component::class, 'component_product')->withPivot('id','cost','o_cost')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTO(Branch::class);
    }

    public function getTotalPriceAttribute()
    {
        $total_ingredients_cost = $this->ingredients()->sum('component_product.cost');
        return $total_ingredients_cost+$this->profit;
    }
}
