<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;

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
            'bio' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque quod tempore rerum odit soluta corporis quis quibusdam assumenda quia unde aperiam fugit, ex nulla alias?',
            'photo' => 'https://picsum.photos/seed/artist1/200/200',
        ]);
    }
}
