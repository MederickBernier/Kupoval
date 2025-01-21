<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Add this line to import the Category model

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Painting',
            'description' => 'Description for Painting',
        ]);

        Category::create([
            'name' => 'Printing',
            'description' => 'Description for Printing',
        ]);
    }
}
