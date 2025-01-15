<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artwork;

class ArtworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artwork::create([
            'title' => 'Sunset Painting',
            'description' => 'A beautiful painting of a sunset.',
            'price' => 200.50,
            'image_path' => 'images/artworks/sunset.jpg',
            'category_id' => 1,
        ]);

        Artwork::create([
            'title' => 'Abstract Art',
            'description' => 'An abstract piece with vibrant colors.',
            'price' => 350.00,
            'image_path' => 'images/artworks/abstract.jpg',
            'category_id' => 2,
        ]);
    }
}
