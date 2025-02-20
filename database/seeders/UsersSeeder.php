<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::create([
            'email' => 'val@kupoval.art',
            'username' => 'val',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $admin->profile()->create([
            'first_name' => 'Valérie',
            'last_name' => 'Labelle',
            'title' => 'Ms',
            'address' => '123 Rue Principale',
            'city' => 'Montréal',
            'zipcode' => 'H2X 3L1',
            'state' => 'Québec',
            'country' => 'Canada',
            'phone' => '514-555-1234',
            'language' => 'frca',
        ]);

        $client = User::create([
            'email' => 'client@kupoval.art',
            'username' => 'client',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);

        $client->profile()->create([
            'first_name' => 'Test',
            'last_name' => 'Client',
            'title' => 'Mr',
            'address' => '456 avenue des Arts',
            'city' => 'Québec',
            'zipcode' => 'G1R 5T8',
            'state' => 'Québec',
            'country' => 'Canada',
            'phone' => '418-555-5678',
            'language' => 'frca',
        ]);
    }
}
