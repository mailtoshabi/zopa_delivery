<?php

namespace Database\Seeders;

use App\Models\MessCategory;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class MessCategorySeeder extends Seeder
{
    public function run()
    {
        $meal1 = MessCategory::create([
            'name' => 'Lunch Plans',
            'slug' => 'Lunch_plans',
            'status' => true,
            'user_id' => 1,
        ]);

        $meal2 = MessCategory::create([
            'name' => 'Single Lunch',
            'slug' => 'Single_lunch',
            'status' => true,
            'user_id' => 1,
        ]);

        // IDs will be used to attach ingredients and remarks later
        $this->command->info('Mess Category created.');
    }
}

