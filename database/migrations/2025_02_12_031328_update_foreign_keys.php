<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_condition_id']);
            $table->foreign('shipping_condition_id')->references('id')->on('shipping_conditions')->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['artwork_id']);
            $table->foreign('artwork_id')->references('id')->on('artworks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_condition_id']);
            $table->foreign('shipping_condition_id')->references('id')->on('shipping_conditions')->onDelete('set null');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['artwork_id']);
            $table->foreign('artwork_id')->references('id')->on('artworks')->onDelete('set null');
        });
    }
};
