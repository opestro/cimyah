<?php

use Botble\Marketplace\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_stores', 'cover_image')) {
            return;
        }

        Schema::table('mp_stores', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('logo');
        });

        Schema::table('mp_stores_translations', function (Blueprint $table) {
            $table->string('cover_image')->nullable();
        });

        foreach (Store::query()->with('metadata')->get() as $store) {
            /**
             * @var Store $store
             */
            $store->update([
                'cover_image' => $store->getMetaData('cover_image', true) ?: $store->getMetaData('background', true),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('mp_stores_translations', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });

        Schema::table('mp_stores', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
    }
};
