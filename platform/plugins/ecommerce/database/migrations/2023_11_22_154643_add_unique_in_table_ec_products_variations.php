<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        try {
            Schema::table('ec_product_variations', function (Blueprint $table) {
                $table->unique(['product_id', 'configurable_product_id']);
            });
        } catch (Throwable) {
        }
    }

    public function down(): void
    {
        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'configurable_product_id']);
        });
    }
};
