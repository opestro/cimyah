<?php

namespace Botble\Theme\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormCollapse;
use Botble\Setting\Forms\SettingForm;
use Botble\Theme\Http\Requests\WebsiteTrackingSettingRequest;

class WebsiteTrackingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $googleTagManagerCode = setting('google_tag_manager_code');
        $googleTagManagerId = setting('google_tag_manager_id', setting('google_analytics'));
        $defaultType = setting('google_tag_manager_type', $googleTagManagerCode ? 'code' : 'id');

        $this
            ->setSectionTitle(trans('packages/theme::theme.settings.website_tracking.title'))
            ->setSectionDescription(trans('packages/theme::theme.settings.website_tracking.description'))
            ->setValidatorClass(WebsiteTrackingSettingRequest::class)
            ->addCollapsible(
                FormCollapse::make('google_tag_manager')
                    ->targetField(
                        'google_tag_manager_type',
                        RadioField::class,
                        RadioFieldOption::make()
                            ->choices([
                                'id' => $tagIdLabel = trans('packages/theme::theme.settings.website_tracking.google_tag_id'),
                                'code' => $codeLabel = trans('packages/theme::theme.settings.website_tracking.google_tag_code'),
                            ])
                            ->selected($targetValue = old('google_tag_manager_type', $defaultType))
                            ->toArray()
                    )
                    ->fieldset(function (WebsiteTrackingSettingForm $form) use ($googleTagManagerId, $tagIdLabel) {
                        $form->add(
                            'google_tag_manager_id',
                            TextField::class,
                            TextFieldOption::make()
                                ->label($tagIdLabel)
                                ->value($googleTagManagerId)
                                ->placeholder(trans('packages/theme::theme.settings.website_tracking.google_tag_id_placeholder'))
                                ->helperText(
                                    sprintf(
                                        "<a href='https://support.google.com/analytics/answer/9539598#find-G-ID' target='_blank'>%s</a>",
                                        'https://support.google.com/analytics/answer/9539598#find-G-ID'
                                    )
                                )
                                ->toArray()
                        );
                    }, targetFieldValue: 'id', isOpened: $targetValue === 'id')
                    ->fieldset(function (WebsiteTrackingSettingForm $form) use ($googleTagManagerCode, $codeLabel) {
                        $form->add(
                            'google_tag_manager_code',
                            CodeEditorField::class,
                            CodeEditorFieldOption::make()
                                ->label($codeLabel)
                                ->value($googleTagManagerCode)
                                ->mode('html')
                                ->helperText(sprintf(
                                    "<a href='https://developers.google.com/tag-platform/gtagjs/install' target='_blank'>%s</a>",
                                    'https://developers.google.com/tag-platform/gtagjs/install'
                                ))
                                ->toArray()
                        );
                    }, targetFieldValue: 'code', isOpened: $targetValue === 'code')
            );
    }
}
