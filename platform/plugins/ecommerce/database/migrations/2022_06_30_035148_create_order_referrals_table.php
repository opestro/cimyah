<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('ec_order_referrals');

        Schema::create('ec_order_referrals', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 39)->nullable();
            $table->string('landing_domain')->nullable();
            $table->string('landing_page')->nullable();
            $table->string('landing_params')->nullable();
            $table->string('referral')->nullable();
            $table->string('gclid')->nullable();
            $table->string('fclid')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->text('referrer_url')->nullable();
            $table->string('referrer_domain')->nullable();
            $table->foreignId('order_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_order_referrals');
    }
};
