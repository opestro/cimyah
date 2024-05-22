<?php

use Botble\Ecommerce\Enums\DeletionRequestStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ec_customer_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('token')->unique();
            $table->string('status', 50)->default(DeletionRequestStatusEnum::WAITING_FOR_CONFIRMATION);
            $table->text('reason')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_customer_deletion_requests');
    }
};
