<?php

namespace Botble\SalePopup;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function removed(): void
    {
        Setting::newQuery()
            ->where('key', 'LIKE', 'sale_popup_%')
            ->delete();
    }
}
