<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\ReturnSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\ReturnSettingRequest;

class ReturnSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.shopping.return_settings'));

        return ReturnSettingForm::create()->renderForm();
    }

    public function update(ReturnSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
