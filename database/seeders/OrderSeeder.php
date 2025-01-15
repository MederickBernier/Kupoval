<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order; // Add this line to import the Order model

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'user_id' => 1,
            'status' => 'completed',
            'total_price' => 450.75,
        ]);

        Order::create([
            'user_id' => 2,
            'status' => 'pending',
            'total_price' => 200.00,
        ]);
    }
}
