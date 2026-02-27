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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('department')->nullable()->after('shipping_address');
            $table->string('phone')->nullable()->after('department');
            $table->string('payment_method')->default('bank_transfer')->after('phone');
            $table->boolean('is_hazmat')->default(false)->after('payment_method');
            $table->text('notes')->nullable()->after('is_hazmat');
            $table->decimal('shipping_fee', 12, 2)->default(0)->after('total_price');
            $table->decimal('tax', 12, 2)->default(0)->after('shipping_fee');
            // Make wilaya_id nullable (it may already be, but let's be safe)
            $table->foreignId('wilaya_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['department', 'phone', 'payment_method', 'is_hazmat', 'notes', 'shipping_fee', 'tax']);
        });
    }
};
