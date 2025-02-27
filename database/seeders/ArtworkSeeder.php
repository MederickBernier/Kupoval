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

        // Artwork names for both artists
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

        // Randomly assign artworks to artists
        // Shuffle indexes 0-9 and split them randomly between artists
        $indexes = range(0, 9);
        shuffle($indexes);

        // Decide how many artworks each artist gets (random split of 10)
        $artist1Count = $faker->numberBetween(4, 6); // Artist 1 gets 4-6 artworks
        $artist2Count = 10 - $artist1Count; // Artist 2 gets the remainder

        // Assign artworks to artist 1
        for ($i = 0; $i < $artist1Count; $i++) {
            $index = $indexes[$i];
            $name = $artworkNames[$index];
            $imageNumber = $index + 1; // Image numbers are 1-10

            Artwork::create([
                'artist_id' => 1,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->paragraph(rand(2, 5)),
                'height' => $faker->randomFloat(1, 20.0, 120.0),
                'width' => $faker->randomFloat(1, 20.0, 100.0),
                'initial_price' => $faker->randomFloat(2, 50.00, 1500.00),
                'image' => 'artworks/test' . $imageNumber . '.jpg',
                'is_on_sale' => $faker->boolean(70),
                'is_featured' => $faker->boolean(30),
                'is_for_event' => $isForEvent = $faker->boolean(20),
                'event_id' => $isForEvent ? rand(1, 5) : null,
            ]);
        }

        // Assign remaining artworks to artist 2
        for ($i = $artist1Count; $i < 10; $i++) {
            $index = $indexes[$i];
            $name = $artworkNames[$index];
            $imageNumber = $index + 1; // Image numbers are 1-10

            Artwork::create([
                'artist_id' => 2, // The second artist (Sophie Moreau)
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->paragraph(rand(2, 5)),
                'height' => $faker->randomFloat(1, 20.0, 120.0),
                'width' => $faker->randomFloat(1, 20.0, 100.0),
                'initial_price' => $faker->randomFloat(2, 150.00, 2000.00),
                'image' => 'artworks/test' . $imageNumber . '.jpg',
                'is_on_sale' => $faker->boolean(70),
                'is_featured' => $faker->boolean(30),
                'is_for_event' => $isForEvent = $faker->boolean(20),
                'event_id' => $isForEvent ? rand(1, 5) : null,
            ]);
        }
    }
}
