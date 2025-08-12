<?php

namespace Database\Seeders;

use App\Http\Utilities\Utility;
use App\Models\Category;
use App\Models\Component;
use App\Models\Customer;
use App\Models\Kitchen;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $adminRole = Role::create(['name' => 'Administrator']);

        $managerRole = Role::create(['name' => 'Manager']);
        $hrRole = Role::create(['name' => 'HR']);
        $executiveRole = Role::create(['name' => 'Executive']);
        $officeStaffRole = Role::create(['name' => 'OfficeStaff']);

        $all_Permission = Permission::create(['name' => 'All Permission']);
        $user_managment = Permission::create(['name' => 'User Managment']);
        $customer_management = Permission::create(['name' => 'Customer Management']);
        $category_management = Permission::create(['name' => 'Category Management']);
        $product_management = Permission::create(['name' => 'Product Management']);
        $enquiry_management = Permission::create(['name' => 'Enquiry Management']);
        $estimate_management = Permission::create(['name' => 'Estimate Management']);

        $adminRole->givePermissionTo([$all_Permission]);
        $managerRole->givePermissionTo([$customer_management, $category_management,$product_management,$enquiry_management,$estimate_management]);
        $hrRole->givePermissionTo([$enquiry_management,$estimate_management]);


        $user1=User::create(['name' => 'Super Admin','email' => 'admin@zopa.in','phone'=>'9809373738','password' => Hash::make('123456'),'email_verified_at'=>now(),'avatar' => 'avatar-1.jpg', 'created_at' => now()]);
        $user1->assignRole('Administrator');
        $user2=User::create(['name' => 'Shameer','email' => 'shameer@gmail.com','phone'=>'9895310132','password' => Hash::make('123456'),'email_verified_at'=>now(),'avatar' => 'avatar-1.jpg','created_at' => now()]);
        $user2->assignRole('Administrator');
        $user3=User::create(['name' => 'Shada Mariyam','email' => 'shada@gmail.com','phone'=>'9809373737','password' => Hash::make('123456'),'email_verified_at'=>now(),'avatar' => 'avatar-1.jpg','created_at' => now()]);
        $user3->assignRole('Manager');

        Kitchen::create(['name'=>'Marakkar House', 'display_name'=>'Kondotty', 'phone'=>'9809373738', 'whatsapp'=>'9809373738','city'=>'Kondotty','district_id'=>Utility::DISTRICT_ID_MPM,'state_id'=>Utility::STATE_ID_KERALA, 'postal_code'=>'673638', 'latitude'=>'11.1460874', 'longitude'=>'75.9634976', 'location_name'=>'Kondotty, Kerala 673638, India', 'delivery_distance'=>'5', 'email'=>'kondotty@zopa.in', 'password'=>Hash::make('123456'), 'created_at' => now(), 'status'=>1, 'is_approved'=>1, 'approved_at'=>now(), 'user_id' => Utility::SUPER_ADMIN_ID]);
        Kitchen::create(['name'=>'Kerala Health Mart', 'display_name'=>'Manjeri', 'phone'=>'9847638678', 'whatsapp'=>'9847638678','city'=>'Manjeri','district_id'=>Utility::DISTRICT_ID_MPM,'state_id'=>Utility::STATE_ID_KERALA, 'postal_code'=>'678956', 'latitude'=>'11.1197497', 'longitude'=>'76.1230190', 'location_name'=>'449F+V6R, Pandikkad Road, Vellarangal, Manjeri, Kerala 676121, India', 'delivery_distance'=>'5', 'email'=>'manjeri@zopa.in', 'password'=>Hash::make('123456'), 'created_at' => now(), 'status'=>1, 'is_approved'=>1, 'approved_at'=>now(), 'user_id' => Utility::SUPER_ADMIN_ID]);

        $this->call([
            CustomerSeeder::class,
            CategorySeeder::class,
            MessCategorySeeder::class,
            WalletGroupSeeder::class,
            MealSeeder::class,
            IngredientSeeder::class,
            RemarkSeeder::class,
        ]);

        // $this->call(MealPurchaseSeeder::class);
        // $this->call(DailyOrderSeeder::class);

    }
}
