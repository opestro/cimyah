<?php

namespace Botble\Ads\Http\Controllers\Settings;

use Botble\Ads\Forms\Settings\AdsSettingForm;
use Botble\Ads\Http\Requests\Settings\AdsSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class AdsSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ads::ads.settings.title'));

        return AdsSettingForm::create()->renderForm();
    }

    public function update(AdsSettingRequest $request)
    {
        if ($request->has('google_adsense_ads_delete_txt')) {
            File::delete(public_path('ads.txt'));

            return $this
                ->httpResponse()
                ->withUpdatedSuccessMessage();
        }

        if ($request->hasFile('ads_google_adsense_txt_file')) {
            $request->file('ads_google_adsense_txt_file')->move(public_path(), 'ads.txt');
        }

        return $this->performUpdate(
            Arr::except($request->validated(), ['ads_google_adsense_txt_file'])
        );
    }
}
