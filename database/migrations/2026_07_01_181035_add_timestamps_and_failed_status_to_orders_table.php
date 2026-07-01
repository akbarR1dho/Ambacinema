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
            // Because changing enum natively in Postgres can be tricky without DBAL or raw SQL,
            // we will drop the old check constraint (if it exists) and add a new one, OR use change()
            // In Laravel 11, change() usually works out of the box for check constraints.
            // Let's try native change() first. If it fails, we fall back to raw SQL.
            $table->timestamp('pending_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
        });
        
        // Postgres raw SQL to add enum value if it's a check constraint
        // Laravel by default creates check constraints for enums like: table_column_check
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'confirmed'::character varying, 'failed'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pending_at', 'confirmed_at', 'failed_at']);
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'confirmed'::character varying]::text[]))");
    }
};
