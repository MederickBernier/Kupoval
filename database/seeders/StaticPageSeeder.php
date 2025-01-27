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
            'title' => 'About Us',
            'content' => '<h2>Welcome to Kupoval</h2>
                          <p>Kupoval is dedicated to bringing unique and inspiring art pieces from creative minds to art enthusiasts worldwide. Our mission is to connect art lovers with exceptional artworks while supporting the artistic community.</p>
                          <h2>Our Mission</h2>
                          <p>To create a space where creativity thrives, making art accessible and appreciated by everyone. We aim to bridge the gap between artists and their audience.</p>
                          <h2>Our Vision</h2>
                          <p>We envision Kupoval as a hub for artistic expression, inspiration, and collaboration, fostering a global art community.</p>',
            'meta_description' => 'Learn more about Kupoval, our mission, and our vision to connect art lovers with inspiring creations worldwide.',
        ]);

    }
}
