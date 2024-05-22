<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\FlashSaleSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\FlashSaleSettingRequest;

class FlashSaleSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.flash_sale.name'));

        return FlashSaleSettingForm::create()->renderForm();
    }

    public function update(FlashSaleSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
