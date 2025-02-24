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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('promo_code')->nullable()->after('total'); // Store the promo code used
            $table->decimal('promo_percent', 5, 2)->default(0)->after('promo_code'); // Store % discount
            $table->decimal('promo_discount', 10, 2)->default(0)->after('promo_percent'); // Store discount amount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'promo_percent', 'promo_discount']);
        });
    }
};
