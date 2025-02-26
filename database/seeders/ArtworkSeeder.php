<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artwork;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ArtworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Artwork names that will be used
        $artworkNames = [
            'Sunset Dreams',
            'Urban Symphony',
            'Whispers of Nature',
            'Celestial Dance',
            'Abstract Emotions',
            'Ocean Memories',
            'Vibrant Reflections',
            'Forest Silence',
            'Midnight Glow',
            'Mountain Echo'
        ];

        // Generate 10 randomized artworks
        for ($i = 1; $i <= 10; $i++) {
            // Get the corresponding name from the array
            $name = $artworkNames[$i - 1];

            // Create randomized artwork
            Artwork::create([
                'artist_id' => 1, // Stable as requested
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->paragraph(rand(2, 5)),
                'height' => $faker->randomFloat(1, 20.0, 120.0),
                'width' => $faker->randomFloat(1, 20.0, 100.0),
                'initial_price' => $faker->randomFloat(2, 50.00, 1500.00),
                'image' => 'artworks/test' . $i . '.jpg', // Following the pattern test1.jpg through test10.jpg
                'is_on_sale' => $faker->boolean(70), // 70% chance of being on sale
                'is_featured' => $faker->boolean(30), // 30% chance of being featured
                'is_for_event' => $isForEvent = $faker->boolean(20), // 20% chance of being for an event
                'event_id' => $isForEvent ? rand(1, 5) : null,
            ]);
        }
    }
}
