<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use Illuminate\Support\Str;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::create([
            'first_name' => 'Valerie',
            'last_name' => 'Labelle',
            'name' => 'Kupoval',
            'slug' => Str::slug('Kupoval'),
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque quod tempore rerum odit soluta corporis quis quibusdam assumenda quia unde aperiam fugit, ex nulla alias?',
            'photo' => 'storage/artist/valerie_labelle.jpg',
        ]);

        // New artist entry for user with ID 3
        Artist::create([
            'first_name' => 'Sophie',
            'last_name' => 'Moreau',
            'name' => 'SophieM',
            'slug' => Str::slug('SophieM'),
            'bio' => 'Contemporary artist specializing in abstract expressionism and mixed media techniques. Sophie\'s work explores the intersection of urban environments and natural landscapes, creating dynamic pieces that challenge perception and invite reflection.',
            'photo' => 'storage/artist/sophie_moreau.jpg',
        ]);
    }
}
