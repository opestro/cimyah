<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->change();
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('customer_email')->nullable()->after('customer_name');
        });
    }

    public function down(): void
    {
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable(false)->change();
            $table->dropColumn(['customer_name', 'customer_email']);
        });
    }
};
