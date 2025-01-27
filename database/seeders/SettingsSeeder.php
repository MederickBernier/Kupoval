<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting; // Add this line to import the Settings model

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'site_name',
            'value' => 'Kupoval'
        ]);

        Setting::create([
            'key' => 'site_address',
            'value' => '123 Rue Principale, Montréal, QC H2X 3L1'
        ]);

        Setting::create([
            'key' => 'site_phone',
            'value' => '+1 (514) 555-1234'
        ]);

        Setting::create([
            'key' => 'site_email',
            'value' => 'contact@kupoval.art',
        ]);
    }
}
