<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Nature',
            'description' => 'Œuvres inspirées par la nature.',
        ]);

        Category::create([
            'name' => 'Abstract',
            'description' => 'Œuvres abstraites avec des formes et couleurs variées.',
        ]);

        Category::create([
            'name' => 'Portraits',
            'description' => 'Peintures de portraits ou visages humains.',
        ]);
    }
}
