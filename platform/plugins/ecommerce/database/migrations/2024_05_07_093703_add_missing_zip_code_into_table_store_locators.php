<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('ec_store_locators', function (Blueprint $table) {
            $table->string('zip_code', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ec_store_locators', function (Blueprint $table) {
            $table->dropColumn('zip_code');
        });
    }
};
