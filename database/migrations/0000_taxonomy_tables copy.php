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
            $table->string('code')->unique(); // e.g., "31" for Oran
            $table->string('name');
            $table->timestamps();
        });

        // ─── Roles ────────────────────────────────────────────── done
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // student, lab, wholesale, admin
            $table->timestamps();
        });

        // ─── Business Categories ───────────────────────── done
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., lab, wholesale
            $table->timestamps();
        });

        // ─── Laboratory Categories ───────────────────────── done
        Schema::create('laboratory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., chemistry, biology, physics
            $table->timestamps();
        });

        // ─── Laboratory Categories ───────────────────────── done
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., chemistry, biology, physics
            $table->timestamps();
        });

        // ─── Universities ───────────────────────────────────────
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('wilaya_id')->constrained();
            $table->timestamps();
        });

        // ─── Departments ────────────────────────────────────────
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('lab_types');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('wilayas');
    }
};
