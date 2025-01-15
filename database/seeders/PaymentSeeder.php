<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment; // Add this line to import the Payment model

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'order_id' => 1, // ID de la commande
            'payment_method' => 'credit_card',
            'amount' => 450.75,
            'status' => 'completed',
            'transaction_id' => 'TXN123456789',
        ]);

        Payment::create([
            'order_id' => 2, // ID d'une autre commande
            'payment_method' => 'paypal',
            'amount' => 200.00,
            'status' => 'pending',
            'transaction_id' => 'TXN987654321',
        ]);
    }
}
