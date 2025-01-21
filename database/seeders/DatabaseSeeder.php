<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\UserProfileSeeder;
use Database\Seeders\ArtistSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\EventsSeeder;
use Database\Seeders\ArtworkSeeder;
use Database\Seeders\SettingsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            UserProfileSeeder::class,
            ArtistSeeder::class,
            CategorySeeder::class,
            EventsSeeder::class,
            ArtworkSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
