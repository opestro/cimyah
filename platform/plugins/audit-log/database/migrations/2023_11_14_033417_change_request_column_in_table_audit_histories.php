<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('audit_histories', function (Blueprint $table) {
            $table->longText('request')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('audit_histories', function (Blueprint $table) {
            $table->text('request')->nullable()->change();
        });
    }
};
