<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('business_id')->constrained('businesses');
            $table->string('code')->unique();
            $table->string('status')->default('pending');
            $table->text('address')->nullable();
            $table->string('department')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('estimation_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimation_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('product_name');
            $table->string('product_type')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimation_request_items');
        Schema::dropIfExists('estimation_requests');
    }
};
