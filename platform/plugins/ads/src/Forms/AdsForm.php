<?php

namespace Botble\Ads\Forms;

use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Http\Requests\AdsRequest;
use Botble\Ads\Models\Ads;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\FormCollapse;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdsForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Ads::class)
            ->setValidatorClass(AdsRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('key', TextField::class, [
                'label' => trans('plugins/ads::ads.key'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/ads::ads.key'),
                    'data-counter' => 255,
                ],
                'default_value' => $this->generateAdsKey(),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make()->toArray())
            ->addCollapsible(
                FormCollapse::make('ads-type-google')
                    ->targetField(
                        'ads_type',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/ads::ads.ads_type'))
                            ->choices([
                                'custom_ad' => trans('plugins/ads::ads.custom_ad'),
                                'google_adsense' => 'Google AdSense',
                            ])
                            ->toArray()
                    )
                    ->fieldset(function (AdsForm $form) {
                        $form->add(
                            'google_adsense_slot_id',
                            TextField::class,
                            TextFieldOption::make()
                                ->label(trans('plugins/ads::ads.google_adsense_slot_id'))
                                ->placeholder('E.g: 1234567890')
                                ->toArray()
                        );
                    })
                    ->fieldset(function (AdsForm $form) {
                        $form
                            ->add('url', TextField::class, [
                                'label' => trans('plugins/ads::ads.url'),
                                'attr' => [
                                    'placeholder' => trans('plugins/ads::ads.url'),
                                    'data-counter' => 255,
                                ],
                            ])
                            ->add('open_in_new_tab', OnOffField::class, [
                                'label' => trans('plugins/ads::ads.open_in_new_tab'),
                                'default_value' => true,
                            ])
                            ->add('image', MediaImageField::class)
                            ->add('tablet_image', MediaImageField::class, [
                                'label' => __('Tablet Image'),
                                'help_block' => [
                                    'text' => __('For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'),
                                ],
                            ])
                            ->add('mobile_image', MediaImageField::class, [
                                'label' => __('Mobile Image'),
                                'help_block' => [
                                    'text' => __('For devices with width less than 768px, if empty, will use the image from the tablet.'),
                                ],
                            ]);
                    }, 'ads_type', 'custom_ad', ! $this->getModel()->ads_type || $this->getModel()->ads_type === 'custom_ad')
                    ->targetValue('google_adsense')
                    ->isOpened($this->getModel()->ads_type === 'google_adsense')
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->when(($adLocations = AdsManager::getLocations()) && count($adLocations) > 1, function () use ($adLocations) {
                $this->add(
                    'location',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('plugins/ads::ads.location'))
                        ->choices($adLocations)
                        ->searchable()
                        ->required()
                        ->toArray()
                );
            })
            ->add(
                'expired_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/ads::ads.expired_at'))
                    ->defaultValue(BaseHelper::formatDate(Carbon::now()->addMonth()))
                    ->toArray()
            )
            ->setBreakFieldPoint('status');
    }

    protected function generateAdsKey(): string
    {
        do {
            $key = strtoupper(Str::random(12));
        } while (Ads::query()->where('key', $key)->exists());

        return $key;
    }
}
