<?php

use Botble\ACL\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('ec_shipment_histories', 'user_type')) {
            Schema::table('ec_shipment_histories', function (Blueprint $table) {
                $table->string('user_type')->default(addslashes(User::class));
            });
        }
    }

    public function down(): void
    {
        Schema::table('ec_shipment_histories', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
