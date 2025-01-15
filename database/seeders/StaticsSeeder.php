<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statics; // Add this line to import the Statics model

class StaticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statics::create([
            'name' => 'total_sales',
            'description' => 'Nombre total d’œuvres vendues.',
            'value' => 50,
        ]);

        Statics::create([
            'name' => 'total_revenue',
            'description' => 'Revenus totaux générés.',
            'value' => 15000.00,
        ]);

        Statics::create([
            'name' => 'total_users',
            'description' => 'Nombre total d’utilisateurs inscrits.',
            'value' => 200,
        ]);
    }
}
