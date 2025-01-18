<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('set null');
            $table->foreignId('shipping_condition_id')->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->decimal('total', 10, 2)->default(0.00);
            $table->string('billing_address', 255);
            $table->string('billing_city', 100);
            $table->string('billing_state', 100);
            $table->string('billing_country', 100);
            $table->string('billing_zipcode', 20);
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_country', 100)->nullable();
            $table->string('shipping_zipcode', 20)->nullable();
            $table->string('recipient_name', 255);
            $table->string('recipient_email', 255);
            $table->string('recipient_phone', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
