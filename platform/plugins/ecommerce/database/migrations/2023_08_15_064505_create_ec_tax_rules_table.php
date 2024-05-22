<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_tax_rules')) {
            return;
        }
        Schema::create('ec_tax_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_id');
            $table->string('country', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->integer('priority')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_tax_rules');
    }
};
