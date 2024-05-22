<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\TrackingSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\TrackingSettingRequest;

class TrackingSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.tracking.name'));

        return TrackingSettingForm::create()->renderForm();
    }

    public function update(TrackingSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
