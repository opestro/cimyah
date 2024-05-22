<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\ProductSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\ProductSettingRequest;

class ProductSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::products.name'));

        return ProductSettingForm::create()->renderForm();
    }

    public function update(ProductSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
