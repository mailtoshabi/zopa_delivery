<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $items = [
            'Rice', 'Veg Curry', 'Fish Curry', 'Pappadam',
            'Achar', 'Payar Upperi', 'Omlet piece', 'Fish Fry'
        ];

        $ingredientIds = [];
        foreach ($items as $item) {
            $ingredient = Ingredient::create([
                'name' => $item,
                'status' => true,
                'user_id' => 1,
            ]);
            $ingredientIds[] = $ingredient->id;
        }

        // Attach to all meals
        Meal::all()->each(function ($meal) use ($ingredientIds) {
            $meal->ingredients()->sync($ingredientIds);
        });

        $this->command->info('Ingredients created and attached to meals.');
    }
}

