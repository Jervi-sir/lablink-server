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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('nif')->nullable(); // Numéro d'Identification Fiscale
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('certificate_url')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('business_category_id')->constrained();
            $table->foreignId('laboratory_category_id')->nullable()->constrained();
            $table->foreignId('wilaya_id')->constrained();

            $table->boolean('is_featured')->default(false);
            $table->json('operating_hours')->nullable();
            $table->json('specializations')->nullable();

            $table->timestamps();
        });

        Schema::create('business_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('platform_id')->constrained();
            $table->string('content');
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_contacts');
        Schema::dropIfExists('businesses');
    }
};
