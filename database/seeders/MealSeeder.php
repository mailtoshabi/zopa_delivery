<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    public function run()
    {
        $meal1 = Meal::create([
            'name' => 'Single Meal',
            'price' => 65,
            'quantity' => 1,
            'status' => true,
            'user_id' => 1,
        ]);

        $meal2 = Meal::create([
            'name' => '30 Meals',
            'price' => 1800,
            'quantity' => 30,
            'status' => true,
            'user_id' => 1,
        ]);

        $meal3 = Meal::create([
            'name' => '15 Meals',
            'price' => 950,
            'quantity' => 15,
            'status' => true,
            'user_id' => 1,
        ]);

        // IDs will be used to attach ingredients and remarks later
        $this->command->info('Meals created.');
    }
}

