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
        Schema::table('products', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('min_booking_time')->nullable();
            $table->boolean('is_available')->default(true);
            
            // Allow equipment type
            $table->string('type')->default('product')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['location', 'supervisor', 'working_hours', 'min_booking_time', 'is_available']);
        });
    }
};
