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
    public function run(): void
    {
        User::create([
            'email' => 'val@kupoval.art',
            'username' => 'val',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'email' => 'client@kupoval.art',
            'username' => 'client',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }
}
