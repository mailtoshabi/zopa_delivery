<?php

namespace Database\Seeders;

use App\Models\Remark;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class RemarkSeeder extends Seeder
{
    public function run()
    {
        $remark = Remark::create([
            'name' => 'Chicken Biriyani once in a week',
            'status' => true,
            'user_id' => 1,
        ]);

        // Attach to all meals
        Meal::all()->each(function ($meal) use ($remark) {
            $meal->remarks()->sync([$remark->id]);
        });

        $this->command->info('Remark created and attached to meals.');
    }
}

