<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Core lookup tables must be created before the users table
     * to satisfy foreign key constraints.
     */
    public function up(): void
    {
        // ─── Wilayas ────────────────────────────────────────────
        Schema::create('wilayas', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('number');
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->timestamps();
        });

        // ─── Roles ────────────────────────────────────────────── done
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // student, lab, wholesale, admin
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->timestamps();
        });

        // ─── Business Categories ───────────────────────── done
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., lab, wholesale
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->timestamps();
        });

        // ─── Laboratory Categories ───────────────────────── done
        Schema::create('laboratory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., chemistry, biology, physics
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->timestamps();
        });

        // ─── Laboratory Categories ───────────────────────── done
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., chemistry, biology, physics
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->timestamps();
        });

        // ─── Universities ───────────────────────────────────────
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('wilaya_id')->constrained();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // ─── Departments ────────────────────────────────────────
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // ─── Platforms ──────────────────────────────────────────
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // whatsapp, email, website, phone, linkedin, instagram, facebook, etc.
            $table->string('en')->nullable();
            $table->string('ar')->nullable();
            $table->string('fr')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('lab_types');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('wilayas');
        Schema::dropIfExists('business_categories');
        Schema::dropIfExists('laboratory_categories');
        Schema::dropIfExists('product_categories');
    }
};
