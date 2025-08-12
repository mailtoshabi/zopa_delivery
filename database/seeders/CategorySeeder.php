<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $meal1 = Category::create([
            'name' => 'Mess',
            'slug' => 'mess',
            'status' => true,
            'user_id' => 1,
        ]);

        $meal2 = Category::create([
            'name' => 'Snacks',
            'slug' => 'snacks',
            'status' => true,
            'user_id' => 1,
        ]);

        // IDs will be used to attach ingredients and remarks later
        $this->command->info('Category created.');
    }
}

