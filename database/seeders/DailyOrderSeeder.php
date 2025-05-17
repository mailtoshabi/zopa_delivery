<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Meal;
use App\Models\MealWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Use a test customer (change this if needed)
        $customer = Customer::first(); // Or Customer::find(1)

        if (!$customer) {
            $this->command->warn('No customers found.');
            return;
        }

        $meal = Meal::first(); // Use the first meal for seeding

        if (!$meal) {
            $this->command->warn('No meals found.');
            return;
        }

        $wallet = MealWallet::where('customer_id', $customer->id)->first();
        if (!$wallet) {
            $this->command->warn('No wallet.');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('daily_meals')->insert([
                'customer_id'     => $customer->id,
                'quantity'        => 1,
                'status'          => 1,
                'date'      => now()->subDays($i), // Makes them "previous"
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $wallet->quantity = $wallet->quantity-10;
        $wallet->save();

        $this->command->info('10 previous daily orders seeded for customer ID: ' . $customer->id);
    }

}
