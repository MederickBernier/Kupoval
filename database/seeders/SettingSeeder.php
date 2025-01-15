<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'site_name',
            'value' => 'Kupoval Art Store',
        ]);

        Setting::create([
            'key' => 'admin_email',
            'value' => 'admin@kupoval.art',
        ]);

        Setting::create([
            'key' => 'default_currency',
            'value' => 'USD',
        ]);

        Setting::create([
            'key' => 'items_per_page',
            'value' => '12',
        ]);
    }
}
