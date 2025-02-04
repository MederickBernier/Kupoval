<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProfile;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        UserProfile::create([
            'user_id' => 1,
            'first_name' => 'Valérie',
            'last_name' => 'Labelle',
            'title' => 'Ms',
            'address' => '123 Rue Principale',
            'city' => 'Montréal',
            'zipcode' => 'H2X 3L1',
            'state' => 'Québec',
            'country' => 'Canada',
            'phone' => '514-555-1234',
        ]);

        UserProfile::create([
            'user_id' => 2,
            'first_name' => 'Test',
            'last_name' => 'Client',
            'title' => 'Mr',
            'address' => '456 avenue des Arts',
            'city' => 'Québec',
            'zipcode' => 'G1R 5T8',
            'state' => 'Québec',
            'country' => 'Canada',
            'phone' => '418-555-5678',
        ]);
    }
}
