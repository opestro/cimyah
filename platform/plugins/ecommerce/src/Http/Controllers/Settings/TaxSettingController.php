<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\TaxSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\TaxSettingRequest;
use Botble\Ecommerce\Tables\TaxTable;
use Illuminate\Http\Request;

class TaxSettingController extends SettingController
{
    public function index(Request $request, TaxTable $taxTable)
    {
        if ($request->expectsJson()) {
            return $taxTable->renderTable();
        }

        $this->pageTitle(trans('plugins/ecommerce::setting.tax.name'));

        $form = TaxSettingForm::create();

        return view('plugins/ecommerce::settings.tax', compact('taxTable', 'form'));
    }

    public function update(TaxSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
