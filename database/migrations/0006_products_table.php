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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            
            // Name (localized)
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('name_fr')->nullable();

            // Description (localized)
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_fr')->nullable();

            $table->decimal('price', 15, 2);
            $table->integer('stock_quantity')->default(0);
            
            $table->string('delivery_time')->nullable();
            $table->string('warranty')->nullable();
            
            $table->json('specifications')->nullable();
            
            $table->enum('type', ['product', 'service'])->default('product');
            $table->boolean('is_active')->default(true);
            $table->string('image_url')->nullable();
            $table->json('images')->nullable();

            
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
