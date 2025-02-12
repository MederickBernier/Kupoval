<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipping_conditions')->insert([
            ['name' => 'Standard Shipping', 'description' => 'Delivered in 5-7 business days', 'fee' => 5.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Express Shipping', 'description' => 'Delivered in 2-3 business days', 'fee' => 15.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Next-Day Delivery', 'description' => 'Delivered the next day', 'fee' => 25.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
