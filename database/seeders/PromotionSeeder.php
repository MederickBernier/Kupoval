<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'title' => 'Black Friday Sale',
            'description' => '50% off on all artworks for Black Friday.',
            'discount' => 50.00,
            'discount_type' => 'percentage',
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ]);

        Promotion::create([
            'title' => 'Holiday Discount',
            'description' => 'Flat $20 off on selected artworks.',
            'discount' => 20.00,
            'discount_type' => 'fixed',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);
    }
}
