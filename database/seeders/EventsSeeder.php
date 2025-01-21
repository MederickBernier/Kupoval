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
        // 2 past events
        Event::create([
            'name' => 'Spring Art Expo',
            'description' => 'A celebration of art inspired by springtime.',
            'start_date' => Carbon::now()->subMonths(2)->toDateString(),
            'end_date' => Carbon::now()->subMonths(2)->addDays(2)->toDateString(),
            'location' => 'Springfield Art Gallery',
        ]);

        Event::create([
            'name' => 'Autumn Showcase',
            'description' => 'A curated collection of autumn-inspired pieces.',
            'start_date' => Carbon::now()->subMonths(1)->toDateString(),
            'end_date' => Carbon::now()->subMonths(1)->addDays(3)->toDateString(),
            'location' => 'Maple Leaf Center',
        ]);

        // 1 event happening today
        Event::create([
            'name' => 'Kupoval Exclusive',
            'description' => 'An exclusive one-day exhibition of special works.',
            'start_date' => Carbon::now()->toDateString(),
            'end_date' => Carbon::now()->toDateString(),
            'location' => 'Kupoval Studio',
        ]);

        // 2 upcoming events
        Event::create([
            'name' => 'Winter Wonderland Exhibition',
            'description' => 'A festive gallery featuring winter-themed artworks.',
            'start_date' => Carbon::now()->addWeeks(1)->toDateString(),
            'end_date' => Carbon::now()->addWeeks(1)->addDays(4)->toDateString(),
            'location' => 'Ice Palace',
        ]);

        Event::create([
            'name' => 'Modern Art Gala',
            'description' => 'A gala evening showcasing the best of modern art.',
            'start_date' => Carbon::now()->addWeeks(3)->toDateString(),
            'end_date' => Carbon::now()->addWeeks(3)->addDays(2)->toDateString(),
            'location' => 'City Art Museum',
        ]);
    }
}
