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
        Schema::table('studios', function (Blueprint $table) {
            $table->unique('name');
        });
        
        Schema::table('studio_types', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->unique('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropUnique(['title']);
        });

        Schema::table('studio_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('studios', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
