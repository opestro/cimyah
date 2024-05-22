<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->string('proof_file')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn('proof_file');
        });
    }
};
