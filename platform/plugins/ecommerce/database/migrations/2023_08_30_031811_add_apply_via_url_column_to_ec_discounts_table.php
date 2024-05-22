<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_discounts', 'apply_via_url')) {
            return;
        }

        Schema::table('ec_discounts', function (Blueprint $table) {
            $table->boolean('apply_via_url')->default(false)->after('min_order_price');
        });
    }

    public function down(): void
    {
        Schema::table('ec_discounts', function (Blueprint $table) {
            $table->dropColumn('apply_via_url');
        });
    }
};
