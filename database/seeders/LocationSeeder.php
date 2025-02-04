<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Charger le fichier JSON
        $json = File::get(database_path('seeders/data/countries.json'));
        $data = json_decode($json, true);

        foreach ($data as $countryData) {
            $country = Country::firstOrCreate([
                'iso2' => $countryData['iso2'],
                'iso3' => $countryData['iso3']
            ], [
                'name' => $countryData['name']
            ]);

            foreach ($countryData['states'] as $stateData) {
                if (!isset($stateData['name']) || empty($stateData['name'])) {
                    continue; // Évite les erreurs si une province est vide
                }

                $state = State::firstOrCreate([
                    'name' => $stateData['name'],
                    'country_id' => $country->id
                ]);

                foreach ($stateData['cities'] as $cityData) {
                    if (is_array($cityData)) {
                        if (isset($cityData['name'])) {
                            $cityName = $cityData['name'];
                        } else {
                            continue;
                        }
                    } else {
                        $cityName = $cityData;
                    }

                    if (!empty($cityName)) {
                        City::firstOrCreate([
                            'name' => $cityName,
                            'state_id' => $state->id
                        ]);
                    }
                }
            }
        }
    }
}
