<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'artwork_id' => 1,
                'quantity' => 2,
                'price' => 150.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 1,
                'artwork_id' => 2,
                'quantity' => 1,
                'price' => 350.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
