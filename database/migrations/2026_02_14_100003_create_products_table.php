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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users');
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('offer_type', ['sale', 'rent']);
            $table->string('unit'); // mg, ml, L, piece, etc.
            $table->decimal('price', 12, 2);
            $table->integer('safety_level')->default(1);
            $table->string('msds_path')->nullable(); // Material Safety Data Sheet
            $table->string('documentations')->nullable(); // Manuals / Spec sheets
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
