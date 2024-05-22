<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('tablet_image')->nullable();
            $table->string('mobile_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('tablet_image');
            $table->dropColumn('mobile_image');
        });
    }
};
