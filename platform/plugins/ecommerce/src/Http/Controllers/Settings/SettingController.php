<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Setting\Http\Controllers\SettingController as BaseSettingController;

abstract class SettingController extends BaseSettingController
{
    protected function saveSettings(array $data, string $prefix = ''): void
    {
        parent::saveSettings($data, EcommerceHelper::getSettingPrefix());
    }
}
