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
            $table->foreignId('business_id')->constrained();
            $table->foreignId('product_category_id')->constrained();
            $table->string('slug')->unique();
            $table->string('name');
            $table->enum('offer_type', ['sale', 'rent']);
            $table->string('unit'); // mg, ml, L, piece, etc.
            $table->decimal('price', 12, 2);
            $table->integer('safety_level')->default(1);
            $table->string('msds_path')->nullable(); // Material Safety Data Sheet
            $table->string('documentations')->nullable(); // Manuals / Spec sheets
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('url');
            $table->string('path')->nullable();
            $table->boolean('is_main')->nullable();
            $table->timestamps();
        });
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('rating');
            $table->string('comment');
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
