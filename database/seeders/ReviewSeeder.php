<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'user_id' => 1,
            'artwork_id' => 2,
            'review' => 'Magnifique œuvre d’art, très détaillée.',
            'rating' => 5,
        ]);

        Review::create([
            'user_id' => 2,
            'artwork_id' => 3,
            'review' => 'Belle réalisation, mais les couleurs ne me plaisent pas.',
            'rating' => 3,
        ]);
    }
}
