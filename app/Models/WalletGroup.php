<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WalletGroup extends Model
{
    use HasFactory;
    // const DIR_PUBLIC = 'meals';

    protected $hidden = ['id'];


    protected $fillable = [
        'name',
        'display_name',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

}
