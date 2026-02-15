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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // The student / buyer
            $table->string('code')->unique(); // Auto-generated characteristic ID (ORD-XXXX-YYYY)
            $table->decimal('total_price', 15, 2);
            $table->text('shipping_address');
            $table->foreignId('wilaya_id')->constrained();
            $table->string('status')->default('pending'); // pending, confirmed, shipped, delivered, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
