<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_brands_translations', function (Blueprint $table) {
            $table->mediumText('description')->nullable()->change();
        });

        Schema::table('ec_product_categories_translations', function (Blueprint $table) {
            $table->mediumText('description')->nullable()->change();
        });
    }
};
