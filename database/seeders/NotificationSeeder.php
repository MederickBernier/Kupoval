<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification; // Add this line to import the Notification model

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::create([
            'user_id' => 1,
            'type' => 'order_update',
            'message' => 'Votre commande #123 a été expédiée.',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => 2,
            'type' => 'promotion',
            'message' => 'Nouvelle promotion : 20% de réduction sur les œuvres abstraites.',
            'is_read' => false,
        ]);
    }
}
