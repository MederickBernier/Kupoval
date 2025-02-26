<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaticPage::create([
            'slug' => 'about',
            'title' => json_encode([
                'enca' => 'About Us',
                'frca' => 'À propos de nous',
            ]),
            'content' => json_encode([
                'enca' => '<h2>Welcome to Kupoval</h2>
                            <p>Kupoval is dedicated to bringing unique and inspiring art pieces from creative minds to art enthusiasts worldwide. Our mission is to connect art lovers with exceptional artworks while supporting the artistic community.</p>
                            <h2 class="mt-4">Our Mission</h2>
                            <p>To create a space where creativity thrives, making art accessible and appreciated by everyone. We aim to bridge the gap between artists and their audience.</p>
                            <h2 class="mt-4">Our Vision</h2>
                            <p>We envision Kupoval as a hub for artistic expression, inspiration, and collaboration, fostering a global art community.</p>',

                'frca' => '<h2>Bienvenue sur Kupoval</h2>
                            <p>Kupoval s\'engage à offrir des œuvres d\'art uniques et inspirantes, créées par des esprits créatifs, aux amateurs d\'art du monde entier. Notre mission est de connecter les passionnés d\'art avec des œuvres exceptionnelles tout en soutenant la communauté artistique.</p>
                            <h2 class="mt-4">Notre mission</h2>
                            <p>Créer un espace où la créativité s\'épanouit, en rendant l\'art accessible et apprécié par tous. Nous visons à réduire l\'écart entre les artistes et leur public.</p>
                            <h2 class="mt-4">Notre vision</h2>
                            <p>Nous imaginons Kupoval comme un centre d\'expression artistique, d\'inspiration et de collaboration, favorisant une communauté artistique mondiale.</p>',
            ]),
            'meta_description' => json_encode([
                'enca' => 'Learn more about Kupoval, our mission, and our vision to connect art lovers with inspiring creations worldwide.',
                'frca' => 'Découvrez-en plus sur Kupoval, notre mission et notre vision visant à connecter les amateurs d\'art avec des créations inspirantes à travers le monde.',
            ]),
        ]);
    }
}
