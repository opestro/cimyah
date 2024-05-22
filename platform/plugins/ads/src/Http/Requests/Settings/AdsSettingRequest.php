<?php

namespace Botble\Ads\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class AdsSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'ads_google_adsense_auto_ads' => ['nullable', 'string'],
            'ads_google_adsense_unit_client_id' => ['nullable', 'string'],
            'ads_google_adsense_txt_file' => ['nullable', 'file', 'mimes:txt', 'max:2048'],
        ];
    }
}
