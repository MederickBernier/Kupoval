<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingCondition;

class ShippingConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingCondition::create([
            'title' => 'Standard Shipping',
            'cost' => 10.00,
            'description' => 'Livraison standard sous 5 à 7 jours.',
        ]);

        ShippingCondition::create([
            'title' => 'Express Shipping',
            'cost' => 25.00,
            'description' => 'Livraison express sous 1 à 2 jours.',
        ]);

        ShippingCondition::create([
            'title' => 'Free Shipping',
            'cost' => 0.00,
            'description' => 'Livraison gratuite pour les commandes de plus de 100€. ',
        ]);
    }
}
