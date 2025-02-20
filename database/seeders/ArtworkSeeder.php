<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artwork;
use Illuminate\Support\Str;

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
            'slug' => Str::slug('Test 1'), // Generate the slug
            'description' => 'Description 1',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test1.jpg',
            'is_on_sale' => true,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 2',
            'slug' => Str::slug('Test 2'), // Generate the slug
            'description' => 'Description 2',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test2.jpg',
            'is_on_sale' => true,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 3',
            'slug' => Str::slug('Test 3'), // Generate the slug
            'description' => 'Description 3',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test3.jpg',
            'is_on_sale' => true,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
        Artwork::create([
            'artist_id' => 1,
            'name' => 'Test 4',
            'slug' => Str::slug('Test 4'), // Generate the slug
            'description' => 'Description 4',
            'height' => 50.5,
            'width' => 40.2,
            'initial_price' => 150.00,
            'image' => 'artworks/test4.jpg',
            'is_on_sale' => true,
            'is_featured' => false,
            'is_for_event' => false,
            'event_id' => null,
        ]);
    }
}
