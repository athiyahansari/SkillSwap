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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('notes');
            $table->decimal('platform_fee', 8, 2)->nullable()->after('hourly_rate');
            $table->decimal('tutor_earnings', 8, 2)->nullable()->after('platform_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'platform_fee', 'tutor_earnings']);
        });
    }
};
