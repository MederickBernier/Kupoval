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
            'artist_id' => 1,
            'name' => 'Test 1',
            'description' => 'Description 1',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test1.jpg',
            'is_on_sale' => false,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 2',
            'description' => 'Description 2',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test2.jpg',
            'is_on_sale' => false,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 3',
            'description' => 'Description 3',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test3.jpg',
            'is_on_sale' => false,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 4',
            'description' => 'Description 4',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test4.jpg',
            'is_on_sale' => false,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 5',
            'description' => 'Description 5',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test5.jpg',
            'is_on_sale' => false,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
    }
}
