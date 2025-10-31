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
        Schema::table('artists', function (Blueprint $table) {
            // Professional profile fields
            $table->text('artist_statement')->nullable()->after('bio');
            $table->text('exhibition_history')->nullable()->after('artist_statement');
            $table->text('awards')->nullable()->after('exhibition_history');
            $table->string('studio_location', 255)->nullable()->after('awards');
            $table->string('profile_video_url', 500)->nullable()->after('studio_location');
            $table->json('specialties')->nullable()->after('profile_video_url'); // Array of specialty areas
            $table->json('techniques')->nullable()->after('specialties'); // Array of techniques used
            $table->integer('experience_years')->nullable()->after('techniques');
            
            // Add indexes for search functionality
            $table->index(['studio_location']);
            $table->index(['experience_years']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropIndex(['studio_location']);
            $table->dropIndex(['experience_years']);
            $table->dropColumn([
                'artist_statement',
                'exhibition_history', 
                'awards',
                'studio_location',
                'profile_video_url',
                'specialties',
                'techniques',
                'experience_years'
            ]);
        });
    }
};
