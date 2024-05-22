<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Ecommerce\Forms\Settings\WebhookSettingForm;
use Botble\Ecommerce\Http\Requests\Settings\WebhookSettingRequest;

class WebhookSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.webhook.name'));

        return WebhookSettingForm::create()->renderForm();
    }

    public function update(WebhookSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
