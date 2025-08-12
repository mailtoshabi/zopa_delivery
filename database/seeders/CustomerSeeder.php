<?php

namespace Database\Seeders;

use App\Http\Utilities\Utility;
use App\Models\Customer;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customer1 = Customer::create([
            'name' => 'Shabeer CM',
            'phone' => 9809373738,
            'whatsapp' => 9809373738,
            'password' => '123@Shabi',
            'district_id' => Utility::DISTRICT_ID_MPM,
            'state_id' => Utility::STATE_ID_KERALA,
            'kitchen_id' => Utility::KITCHEN_KDY,
            'customer_type' => Utility::CUSTOMER_TYPE_IND,
            'is_approved' => 1,
            'status' => true,
            'language' => 'en',
            'user_id' => 1,
        ]);

        $customer1 = Customer::create([
            'name' => 'Shameer CM',
            'phone' => 9847638678,
            'whatsapp' => 9847638678,
            'password' => '123@Shami',
            'district_id' => Utility::DISTRICT_ID_MPM,
            'state_id' => Utility::STATE_ID_KERALA,
            'kitchen_id' => 2,
            'customer_type' => Utility::CUSTOMER_TYPE_IND,
            'is_approved' => 1,
            'status' => true,
            'language' => 'en',
            'user_id' => 1,
        ]);

        // IDs will be used to attach ingredients and remarks later
        $this->command->info('Customer created.');
    }
}

