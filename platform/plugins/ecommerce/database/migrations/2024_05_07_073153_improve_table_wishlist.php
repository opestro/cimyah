<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        try {
            if (Schema::hasColumn('ec_wish_lists', 'id')) {
                Schema::table('ec_wish_lists', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }

            Schema::dropIfExists('ec_wish_lists_tmp');

            DB::statement('CREATE TABLE ec_wish_lists_tmp AS SELECT * FROM ec_wish_lists');

            DB::statement('TRUNCATE TABLE ec_wish_lists');

            Schema::table('ec_wish_lists', function (Blueprint $table) {
                $table->primary(['customer_id', 'product_id']);
            });

            DB::table('ec_wish_lists_tmp')->oldest()->chunk(1000, function ($chunked) {
                DB::table('ec_wish_lists')->insertOrIgnore(array_map(fn ($item) => (array) $item, $chunked->toArray()));
            });

            Schema::dropIfExists('ec_wish_lists_tmp');
        } catch (Throwable) {
            Schema::dropIfExists('ec_wish_lists_tmp');
        }
    }
};
