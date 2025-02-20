<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userProfile = UserProfile::first(); // Assuming we seed for first user

        // Billing Address
        Address::create([
            'user_profile_id' => $userProfile->id,
            'type' => 'billing',
            'address' => '123 Main St',
            'city' => 'Montreal',
            'state' => 'Quebec',
            'country' => 'Canada',
            'zipcode' => 'H2X 3L1',
        ]);

        // Shipping Address
        Address::create([
            'user_profile_id' => $userProfile->id,
            'type' => 'shipping',
            'address' => '456 Another St',
            'city' => 'Quebec City',
            'state' => 'Quebec',
            'country' => 'Canada',
            'zipcode' => 'G1R 5T8',
        ]);
    }
}
