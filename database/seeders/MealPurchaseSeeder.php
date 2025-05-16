<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Meal;
use App\Models\MealWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MealPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        // Use a test customer (change this if needed)
        $customer = Customer::first(); // Or Customer::find(1)

        if (!$customer) {
            $this->command->warn('No customers found.');
            return;
        }

        $meal = Meal::orderBy('id','desc')->first(); // Use the first meal for seeding

        if (!$meal) {
            $this->command->warn('No meals found.');
            return;
        }

        $wallet = MealWallet::where('customer_id', $customer->id)->first();
        if (!$wallet) {
            $this->command->warn('No wallet.');
            return;
        }

        for ($i = 1; $i <= 1; $i++) {
            DB::table('customer_meal')->insert([
                'invoice_no'      => 'INV-' . strtoupper(uniqid()),
                'customer_id'     => $customer->id,
                'meal_id'         => $meal->id,
                'price'           => $meal->price,
                'quantity'        => $meal->quantity,
                'pay_method'      => 1,
                'discount'        => 0,
                'delivery_charge' => 0,
                'is_paid'         => 1,
                'status'          => 1,
                'created_at'      => now()->subDays($i), // Makes them "previous"
                'updated_at'      => now()->subDays($i),
            ]);
        }

        $wallet->quantity = $meal->quantity;
        $wallet->save();

        $this->command->info('1 previous daily orders seeded for customer ID: ' . $customer->id);
    }
}
