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
        Schema::table('estimation_requests', function (Blueprint $table) {
            $table->decimal('extra_fee', 12, 2)->default(0)->after('notes');
            $table->text('quoting_notes')->nullable()->after('extra_fee');
        });
    }

    public function down(): void
    {
        Schema::table('estimation_requests', function (Blueprint $table) {
            $table->dropColumn(['extra_fee', 'quoting_notes']);
        });
    }
};
