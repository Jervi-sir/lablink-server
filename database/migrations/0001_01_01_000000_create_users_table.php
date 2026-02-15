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

        // ─── Roles ──────────────────────────────────────────────
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // student, lab, wholesale, admin
            $table->timestamps();
        });

        // ─── Lab Types ──────────────────────────────────────────
        Schema::create('lab_types', function (Blueprint $table) {
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

        // ─── Users ──────────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // ─── Password Reset Tokens ─────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ─── Sessions ───────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
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
