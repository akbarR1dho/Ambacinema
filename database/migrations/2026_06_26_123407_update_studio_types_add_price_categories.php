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
        Schema::table('studio_types', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->integer('price_weekday')->default(50000)->after('name');
            $table->integer('price_friday')->default(50000)->after('price_weekday');
            $table->integer('price_weekend')->default(60000)->after('price_friday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studio_types', function (Blueprint $table) {
            $table->dropColumn(['price_weekday', 'price_friday', 'price_weekend']);
            $table->integer('price')->default(50000);
        });
    }
};
