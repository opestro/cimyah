<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('mp_stores_translations')) {
            Schema::create('mp_stores_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->foreignId('mp_stores_id');
                $table->string('name')->nullable();
                $table->string('description', 400)->nullable();
                $table->longText('content')->nullable();
                $table->string('address')->nullable();
                $table->string('company')->nullable();

                $table->primary(['lang_code', 'mp_stores_id'], 'mp_stores_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_stores_translations');
    }
};
