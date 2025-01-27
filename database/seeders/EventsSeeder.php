<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $months = range(1, 12);
        $totalEvents = 30;
        $eventsPerMonth = array_fill_keys($months, 0);

        // Randomly distribute events across the months
        for ($i = 0; $i < $totalEvents; $i++) {
            $randomMonth = $faker->randomElement($months);
            $eventsPerMonth[$randomMonth]++;
        }

        foreach ($eventsPerMonth as $month => $count) {
            for ($i = 0; $i < $count; $i++) {
                $startDate = Carbon::createFromDate(2025, $month, $faker->numberBetween(1, 28));
                $endDate = (clone $startDate)->addDays($faker->numberBetween(1, 5)); // Events last 1 to 5 days

                Event::create([
                    'name' => ucfirst($faker->words(3, true)), // Random name
                    'description' => $faker->sentence(), // Random description
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'location' => $faker->city() . ' ' . $faker->companySuffix(), // Random location
                ]);
            }
        }
    }
}
