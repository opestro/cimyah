<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_product_cross_sale_relations', 'apply_to_all_variations')) {
            return;
        }

        Schema::table('ec_product_cross_sale_relations', function (Blueprint $table) {
            $table->boolean('is_variant')->default(false);
            $table->decimal('price', 15)->default(0)->nullable();
            $table->string('price_type')->default('fixed');
            $table->boolean('apply_to_all_variations')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_cross_sale_relations', function (Blueprint $table) {
            $table->dropColumn(['is_variant', 'price', 'price_type', 'apply_to_all_variations']);
        });
    }
};
