<?php

namespace Botble\SocialLogin\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Http\Controllers\SettingController;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Forms\SocialLoginSettingForm;
use Botble\SocialLogin\Http\Requests\Settings\SocialLoginSettingRequest;
use Illuminate\Support\Arr;

class SocialLoginSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/social-login::social-login.settings.title'));

        return SocialLoginSettingForm::create()->renderForm();
    }

    public function update(SocialLoginSettingRequest $request): BaseHttpResponse
    {
        $prefix = 'social_login_';

        $data = [
            "{$prefix}enable" => $request->input("{$prefix}enable"),
        ];

        foreach (SocialService::getProviders() as $provider => $item) {
            $prefix = 'social_login_' . $provider . '_';

            $data["{$prefix}enable"] = $request->input("{$prefix}enable");

            foreach ($item['data'] as $input) {
                if (
                    ! in_array(app()->environment(), SocialService::getEnvDisableData()) ||
                    ! in_array($input, Arr::get($item, 'disable', []))
                ) {
                    $data["{$prefix}{$input}"] = $request->input("{$prefix}{$input}");
                }
            }
        }

        return $this->performUpdate($data);
    }
}
