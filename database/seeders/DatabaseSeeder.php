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
use Database\Seeders\LocationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,         // Global settings (runs first)
            ShippingConditionsSeeder::class, // Required before orders
            UsersSeeder::class,            // Creates base users
            UserProfileSeeder::class,      // Requires users to exist
            ArtistSeeder::class,           // Creates artists
            CategorySeeder::class,         // Creates categories
            EventsSeeder::class,           // Creates events
            ArtworkSeeder::class,          // Requires artists to exist
            StaticPageSeeder::class,       // Static pages (independent)
        ]);
    }
}
