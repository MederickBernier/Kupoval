<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('wishlist')->insert([
            [
                'user_id' => 1, // ID de l'utilisateur
                'artwork_id' => 2, // ID de l'œuvre
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, // ID de l'utilisateur
                'artwork_id' => 3, // ID d'une autre œuvre
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
