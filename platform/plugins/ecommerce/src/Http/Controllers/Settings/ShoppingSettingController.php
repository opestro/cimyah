<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\ShoppingSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\ShoppingSettingRequest;

class ShoppingSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.shopping.name'));

        return ShoppingSettingForm::create()->renderForm();
    }

    public function update(ShoppingSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
