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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->boolean('is_final')->default(false);
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // The student / buyer
            $table->string('code')->unique(); // Auto-generated characteristic ID (ORD-XXXX-YYYY)
            $table->decimal('total_price', 15, 2);
            $table->text('shipping_address');
            $table->foreignId('wilaya_id')->constrained();
            $table->foreignId('order_status_id')->constrained(); // pending, confirmed, shipped, delivered, cancelled
            $table->timestamps();
        });
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // Price snapshot at time of order
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
