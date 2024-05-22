<?php

namespace Botble\Marketplace\Http\Controllers\Settings;

use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Setting\Http\Controllers\SettingController as BaseSettingController;

abstract class SettingController extends BaseSettingController
{
    protected function saveSettings(array $data, string $prefix = ''): void
    {
        parent::saveSettings($data, MarketplaceHelper::getSettingKey());
    }
}
