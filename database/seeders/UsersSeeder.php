<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'email' => 'val@kupoval.art',
            'username' => 'val',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ])->profile()->create([
            'first_name' => 'Valérie',
            'last_name' => 'Labelle',
            'title' => 'Ms',
            'phone' => '514-555-1234',
            'language' => 'frca',
        ]);

        User::create([
            'email' => 'client@kupoval.art',
            'username' => 'client',
            'password' => bcrypt('password'),
            'role' => 'client',
        ])->profile()->create([
            'first_name' => 'Test',
            'last_name' => 'Client',
            'title' => 'Mr',
            'phone' => '418-555-5678',
            'language' => 'frca',
        ]);
    }
}
