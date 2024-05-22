<?php

namespace Botble\Ads\Forms\Settings;

use Botble\Ads\Http\Requests\Settings\AdsSettingRequest;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;

class AdsSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ads::ads.settings.title'))
            ->setSectionDescription(trans('plugins/ads::ads.settings.description'))
            ->setValidatorClass(AdsSettingRequest::class);

        $googleAdSenseLink = Html::link('http://www.google.com/adsense', 'Google AdSense', ['target' => '_blank']);

        $this
            ->add(
                'ads_google_adsense_auto_ads',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/ads::ads.settings.google_adsense_auto_ads_snippet'))
                    ->helperText(trans('plugins/ads::ads.settings.google_adsense_auto_ads_snippet_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('ads_google_adsense_auto_ads'))
                    ->toArray()
            )
            ->add(
                'ads_google_adsense_unit_client_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ads::ads.settings.google_adsense_unit_ads_client_id'))
                    ->helperText(trans('plugins/ads::ads.settings.google_adsense_unit_ads_client_id_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('ads_google_adsense_unit_client_id'))
                    ->placeholder('ca-pub-123456789')
                    ->toArray()
            )
            ->add(
                'ads_google_adsense_what_is_client_id',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/ads::partials.google-adsense.client-id')
                    ->toArray()
            )
            ->add('ads_google_adsense_txt_file', 'file', [
                'label' => __('Your Google Adsense ads.txt'),
            ])
            ->add(
                'ads_google_adsense_txt',
                HtmlField::class,
                HtmlFieldOption::make()->view('plugins/ads::partials.google-adsense.txt')->toArray()
            )
        ;
    }
}
