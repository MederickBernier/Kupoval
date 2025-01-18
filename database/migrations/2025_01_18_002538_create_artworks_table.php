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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->onDelete('set null');
            $table->string('name',255);
            $table->text('description')->nullable();
            $table->decimal('height',10,2);
            $table->decimal('width',10,2);
            $table->string('image',255)->nullable();
            $table->decimal('initial_price',10,2)->default(0);
            $table->boolean('is_on_sale')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_for_event')->default(false);
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
