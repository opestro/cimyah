<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_discount_product_categories')) {
            return;
        }

        Schema::create('ec_discount_product_categories', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('product_category_id');
            $table->primary(['discount_id', 'product_category_id'], 'discount_product_categories_primary_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_discount_product_categories');
    }
};
