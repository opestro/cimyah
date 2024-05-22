<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\CustomerSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\CustomerSettingRequest;

class CustomerSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.customer.name'));

        return CustomerSettingForm::create()->renderForm();
    }

    public function update(CustomerSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
