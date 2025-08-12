<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;

    const DIR_STORAGE = 'storage/categories/';
    const DIR_PUBLIC = 'categories/';

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['status'=>'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'name'])
        ->setDescriptionForEvent(fn(string $eventName) => "The Category has been {$eventName}");
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

}
