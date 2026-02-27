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
    Schema::table('products', function (Blueprint $table) {
      $table->string('sku')->nullable()->after('name');
      $table->text('description')->nullable()->after('sku');
      $table->text('summary')->nullable()->after('description');
      $table->json('specifications')->nullable()->after('summary');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn(['sku', 'description', 'summary', 'specifications']);
    });
  }
};
