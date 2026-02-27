<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the PostgreSQL check constraint created by the enum
            DB::statement('ALTER TABLE products DROP CONSTRAINT IF EXISTS products_offer_type_check');
            $table->string('offer_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Re-adding the enum would require the check constraint to be rebuilt by Laravel
            $table->enum('offer_type', ['sale', 'rent'])->nullable(false)->change();
        });
    }
};
