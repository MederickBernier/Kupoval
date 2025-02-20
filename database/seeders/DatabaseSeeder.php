<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class, // Global settings (runs first)
            ShippingConditionsSeeder::class, // Required before orders
            UsersSeeder::class, // Creates base users
            AddressSeeder::class, // Requires user profiles to exist
            ArtistSeeder::class, // Creates artists
            CategorySeeder::class, // Creates categories
            EventsSeeder::class, // Creates events
            ArtworkSeeder::class, // Requires artists to exist
            StaticPageSeeder::class, // Static pages (independent)
        ]);
    }
}
