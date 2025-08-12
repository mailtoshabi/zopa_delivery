<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\WalletGroup;
use Illuminate\Database\Seeder;

class WalletGroupSeeder extends Seeder
{
    public function run()
    {
        $meal1 = WalletGroup::create([
            'name' => 'Meals',
            'display_name' => 'Meals',
            'status' => true,
        ]);

        $meal2 = WalletGroup::create([
            'name' => 'Biriyani',
            'display_name' => 'Biriyani',
            'status' => true,
        ]);

        // IDs will be used to attach ingredients and remarks later
        $this->command->info('Wallet Group created.');
    }
}

