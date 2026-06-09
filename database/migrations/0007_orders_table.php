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
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lab_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('request_estimation'); // request_estimation, estimation_provided, confirmed, rejected, completed
            $table->decimal('total_price', 10, 2)->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('student_last_viewed_at')->nullable();
            $table->timestamp('lab_last_viewed_at')->nullable();
            $table->string('contract_pdf_url')->nullable();

            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('suggested_by'); // 'student' or 'lab'
            $table->decimal('suggested_price', 10, 2);
            $table->string('status')->default('pending'); // pending, accepted, rejected
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
