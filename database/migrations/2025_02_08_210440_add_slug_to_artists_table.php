<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Artist;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        foreach (Artist::all() as $artist) {
            $artist->slug = Str::slug($artist->name ?? $artist->first_name . '-' . $artist->last_name);
            $artist->save();
        }

        Schema::table('artists', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
