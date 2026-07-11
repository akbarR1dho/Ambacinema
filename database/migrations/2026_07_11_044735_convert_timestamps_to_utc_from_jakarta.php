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
        $tablesAndColumns = [
            'users' => ['created_at', 'updated_at', 'email_verified_at'],
            'movies' => ['created_at', 'updated_at'],
            'studios' => ['created_at', 'updated_at'],
            'studio_types' => ['created_at', 'updated_at'],
            'showtimes' => ['start_time', 'end_time', 'created_at', 'updated_at'],
            'seats' => ['created_at', 'updated_at'],
            'orders' => ['pending_at', 'confirmed_at', 'failed_at', 'created_at', 'updated_at'],
        ];

        foreach ($tablesAndColumns as $table => $columns) {
            foreach ($columns as $column) {
                \Illuminate\Support\Facades\DB::statement("UPDATE {$table} SET {$column} = {$column} - INTERVAL '7 hours' WHERE {$column} IS NOT NULL;");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablesAndColumns = [
            'users' => ['created_at', 'updated_at', 'email_verified_at'],
            'movies' => ['created_at', 'updated_at'],
            'studios' => ['created_at', 'updated_at'],
            'studio_types' => ['created_at', 'updated_at'],
            'showtimes' => ['start_time', 'end_time', 'created_at', 'updated_at'],
            'seats' => ['created_at', 'updated_at'],
            'orders' => ['pending_at', 'confirmed_at', 'failed_at', 'created_at', 'updated_at'],
        ];

        foreach ($tablesAndColumns as $table => $columns) {
            foreach ($columns as $column) {
                \Illuminate\Support\Facades\DB::statement("UPDATE {$table} SET {$column} = {$column} + INTERVAL '7 hours' WHERE {$column} IS NOT NULL;");
            }
        }
    }
};
