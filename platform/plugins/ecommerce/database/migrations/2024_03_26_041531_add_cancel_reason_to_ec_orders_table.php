<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('is_finished');
            $table->string('cancellation_reason_description')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancellation_reason_description']);
        });
    }
};
