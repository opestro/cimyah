<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_discounts', 'description')) {
            return;
        }

        Schema::table('ec_discounts', function (Blueprint $table) {
            $table->boolean('display_at_checkout')->default(false)->after('apply_via_url');
            $table->string('description', 400)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ec_discounts', function (Blueprint $table) {
            $table->dropColumn(['display_at_checkout', 'description']);
        });
    }
};
