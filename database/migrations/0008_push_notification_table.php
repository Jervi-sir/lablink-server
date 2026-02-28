<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('notification_settings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('expo_push_token')->nullable();
      $table->boolean('enabled')->default(true);
      $table->boolean('enable_order_status_updates')->default(true);
      $table->boolean('enable_chat_messages')->default(true);
      $table->boolean('enable_promotions')->default(false);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('notification_settings');
  }
};
