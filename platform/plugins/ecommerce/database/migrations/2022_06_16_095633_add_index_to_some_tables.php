<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->index(['product_id', 'customer_id', 'status'], 'review_relation_index');
        });

        Schema::table('ec_wish_lists', function (Blueprint $table) {
            $table->index(['product_id', 'customer_id'], 'wishlist_relation_index');
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table) {
            $table->index(['attribute_id', 'variation_id'], 'attribute_variation_index');
        });

        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->index(['product_id', 'configurable_product_id'], 'configurable_product_index');
        });

        Schema::table('ec_product_attributes', function (Blueprint $table) {
            $table->index(['attribute_set_id', 'status'], 'attribute_set_status_index');
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->index('sale_type', 'sale_type_index');
            $table->index('start_date', 'start_date_index');
            $table->index('end_date', 'end_date_index');
            $table->index('sale_price', 'sale_price_index');
            $table->index('is_variation', 'is_variation_index');
        });
    }

    public function down(): void
    {
        Schema::table('ec_reviews', function (Blueprint $table) {
            $table->dropIndex('review_relation_index');
        });

        Schema::table('ec_wish_lists', function (Blueprint $table) {
            $table->dropIndex('wishlist_relation_index');
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table) {
            $table->dropIndex('attribute_variation_index');
        });

        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->dropIndex('configurable_product_index');
        });

        Schema::table('ec_product_attributes', function (Blueprint $table) {
            $table->dropIndex('attribute_set_status_index');
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropIndex('sale_type_index');
            $table->dropIndex('start_date_index');
            $table->dropIndex('end_date_index');
            $table->dropIndex('sale_price_index');
            $table->dropIndex('is_variation_index');
        });
    }
};
