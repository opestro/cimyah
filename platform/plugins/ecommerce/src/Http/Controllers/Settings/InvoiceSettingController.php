<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\InvoiceSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\InvoiceSettingRequest;

class InvoiceSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.invoice.name'));

        return InvoiceSettingForm::create()->renderForm();
    }

    public function update(InvoiceSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
