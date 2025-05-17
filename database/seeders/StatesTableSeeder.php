<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Andhra Pradesh', 'code' => 'AP', 'gst_code' => '37', 'type' => 'state'],
            ['name' => 'Arunachal Pradesh', 'code' => 'AR', 'gst_code' => '12', 'type' => 'state'],
            ['name' => 'Assam', 'code' => 'AS', 'gst_code' => '18', 'type' => 'state'],
            ['name' => 'Bihar', 'code' => 'BR', 'gst_code' => '10', 'type' => 'state'],
            ['name' => 'Chhattisgarh', 'code' => 'CT', 'gst_code' => '22', 'type' => 'state'],
            ['name' => 'Goa', 'code' => 'GA', 'gst_code' => '30', 'type' => 'state'],
            ['name' => 'Gujarat', 'code' => 'GJ', 'gst_code' => '24', 'type' => 'state'],
            ['name' => 'Haryana', 'code' => 'HR', 'gst_code' => '06', 'type' => 'state'],
            ['name' => 'Himachal Pradesh', 'code' => 'HP', 'gst_code' => '02', 'type' => 'state'],
            ['name' => 'Jharkhand', 'code' => 'JH', 'gst_code' => '20', 'type' => 'state'],
            ['name' => 'Karnataka', 'code' => 'KA', 'gst_code' => '29', 'type' => 'state'],
            ['name' => 'Kerala', 'code' => 'KL', 'gst_code' => '32', 'type' => 'state'],
            ['name' => 'Madhya Pradesh', 'code' => 'MP', 'gst_code' => '23', 'type' => 'state'],
            ['name' => 'Maharashtra', 'code' => 'MH', 'gst_code' => '27', 'type' => 'state'],
            ['name' => 'Manipur', 'code' => 'MN', 'gst_code' => '14', 'type' => 'state'],
            ['name' => 'Meghalaya', 'code' => 'ML', 'gst_code' => '17', 'type' => 'state'],
            ['name' => 'Mizoram', 'code' => 'MZ', 'gst_code' => '15', 'type' => 'state'],
            ['name' => 'Nagaland', 'code' => 'NL', 'gst_code' => '13', 'type' => 'state'],
            ['name' => 'Odisha', 'code' => 'OR', 'gst_code' => '21', 'type' => 'state'],
            ['name' => 'Punjab', 'code' => 'PB', 'gst_code' => '03', 'type' => 'state'],
            ['name' => 'Rajasthan', 'code' => 'RJ', 'gst_code' => '08', 'type' => 'state'],
            ['name' => 'Sikkim', 'code' => 'SK', 'gst_code' => '11', 'type' => 'state'],
            ['name' => 'Tamil Nadu', 'code' => 'TN', 'gst_code' => '33', 'type' => 'state'],
            ['name' => 'Telangana', 'code' => 'TG', 'gst_code' => '36', 'type' => 'state'],
            ['name' => 'Tripura', 'code' => 'TR', 'gst_code' => '16', 'type' => 'state'],
            ['name' => 'Uttar Pradesh', 'code' => 'UP', 'gst_code' => '09', 'type' => 'state'],
            ['name' => 'Uttarakhand', 'code' => 'UK', 'gst_code' => '05', 'type' => 'state'],
            ['name' => 'West Bengal', 'code' => 'WB', 'gst_code' => '19', 'type' => 'state'],

            // Union Territories
            ['name' => 'Andaman and Nicobar Islands', 'code' => 'AN', 'gst_code' => '35', 'type' => 'union_territory'],
            ['name' => 'Chandigarh', 'code' => 'CH', 'gst_code' => '04', 'type' => 'union_territory'],
            ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'code' => 'DN', 'gst_code' => '26', 'type' => 'union_territory'],
            ['name' => 'Lakshadweep', 'code' => 'LD', 'gst_code' => '31', 'type' => 'union_territory'],
            ['name' => 'Delhi', 'code' => 'DL', 'gst_code' => '07', 'type' => 'union_territory'],
            ['name' => 'Puducherry', 'code' => 'PY', 'gst_code' => '34', 'type' => 'union_territory'],
            ['name' => 'Ladakh', 'code' => 'LA', 'gst_code' => '38', 'type' => 'union_territory'],
            ['name' => 'Jammu and Kashmir', 'code' => 'JK', 'gst_code' => '01', 'type' => 'union_territory'],
        ];

        DB::table('states')->insert($states);
    }
}
