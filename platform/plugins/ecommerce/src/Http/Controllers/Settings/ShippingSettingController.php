<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Facades\Assets;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\Settings\ShippingSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\ShippingSettingRequest;
use Botble\Ecommerce\Models\Shipping;

class ShippingSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.shipping.name'));

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly(['vendor/core/plugins/ecommerce/js/shipping.js'])
            ->addScripts(['input-mask']);

        $form = ShippingSettingForm::create();

        $shipping = Shipping::query()
            ->with([
                'rules' => function ($query) {
                    $query->withCount('items');
                },
            ])
            ->get();

        return view('plugins/ecommerce::settings.shipping', compact('shipping', 'form'));
    }

    public function update(ShippingSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
