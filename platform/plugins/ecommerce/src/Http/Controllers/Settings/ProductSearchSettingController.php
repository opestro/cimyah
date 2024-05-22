<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\ProductSearchSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\ProductSearchSettingRequest;

class ProductSearchSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.product_search.name'));

        return ProductSearchSettingForm::create()->renderForm();
    }

    public function update(ProductSearchSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
