<?php

use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        rescue(function () {
            if (setting('ecommerce_enable_recaptcha_in_register_page')) {
                DB::table('settings')->insertOrIgnore([
                    'id' => BaseModel::getTypeOfId() === 'BIGINT' ? null : (new BaseModel())->newUniqueId(),
                    'key' => 'enable_recaptcha_botble_ecommerce_forms_fronts_auth_register_form',
                    'value' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            if (setting('ecommerce_enable_math_captcha_in_register_page')) {
                DB::table('settings')->insertOrIgnore([
                    'id' => BaseModel::getTypeOfId() === 'BIGINT' ? null : (new BaseModel())->newUniqueId(),
                    'key' => 'enable_math_captcha_botble_ecommerce_forms_fronts_auth_register_form',
                    'value' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        });
    }
};
