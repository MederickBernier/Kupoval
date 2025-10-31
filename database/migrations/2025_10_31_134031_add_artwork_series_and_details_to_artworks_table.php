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
        Schema::table('artworks', function (Blueprint $table) {
            // Series and Organization
            $table->string('series_name', 255)->nullable()->after('name');
            $table->integer('creation_year')->nullable()->after('series_name');
            
            // Detailed Artwork Information
            $table->string('medium', 255)->nullable()->after('creation_year');
            $table->text('technique_notes')->nullable()->after('medium');
            $table->string('dimensions', 255)->nullable()->after('technique_notes'); // e.g., "24 x 36 inches" or "61 x 91.4 cm"
            $table->string('depth', 100)->nullable()->after('dimensions'); // For 3D works
            $table->string('weight', 100)->nullable()->after('depth'); // Weight if relevant
            $table->string('edition_info', 255)->nullable()->after('weight'); // Edition number, limited edition info
            $table->string('condition', 100)->nullable()->after('edition_info'); // Excellent, Good, Fair, etc.
            $table->text('provenance')->nullable()->after('condition'); // History of ownership
            $table->boolean('is_framed')->default(false)->after('provenance');
            $table->text('framing_details')->nullable()->after('is_framed'); // Frame description if applicable
            $table->text('care_instructions')->nullable()->after('framing_details');
            
            // Add indexes for filtering and search
            $table->index(['series_name']);
            $table->index(['creation_year']);
            $table->index(['medium']);
            $table->index(['condition']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropIndex(['series_name']);
            $table->dropIndex(['creation_year']);
            $table->dropIndex(['medium']);
            $table->dropIndex(['condition']);
            
            $table->dropColumn([
                'series_name',
                'creation_year',
                'medium',
                'technique_notes',
                'dimensions',
                'depth',
                'weight',
                'edition_info',
                'condition',
                'provenance',
                'is_framed',
                'framing_details',
                'care_instructions'
            ]);
        });
    }
};
