<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\GeneralSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\GeneralSettingRequest;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.general.name'));

        return GeneralSettingForm::create()->renderForm();
    }

    public function update(GeneralSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
