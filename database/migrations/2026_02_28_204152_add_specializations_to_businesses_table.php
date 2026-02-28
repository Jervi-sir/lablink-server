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
    if (!Schema::hasColumn('businesses', 'specializations')) {
      Schema::table('businesses', function (Blueprint $table) {
        $table->json('specializations')->after('operating_hours')->nullable();
      });
    }

    if (!Schema::hasColumn('businesses', 'registration_no')) {
      Schema::table('businesses', function (Blueprint $table) {
        $table->string('registration_no')->after('nif')->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    if (Schema::hasColumn('businesses', 'specializations')) {
      Schema::table('businesses', function (Blueprint $table) {
        $table->dropColumn('specializations');
      });
    }

    if (Schema::hasColumn('businesses', 'registration_no')) {
      Schema::table('businesses', function (Blueprint $table) {
        $table->dropColumn('registration_no');
      });
    }
  }
};
