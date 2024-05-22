<?php

namespace Botble\Payment\Concerns\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Supports\Helper;
use Botble\Payment\Supports\PaymentHelper;

trait HasAvailableCountriesField
{
    protected function addAvailableCountriesField(string $paymentMethod): static
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/setting.js');

        $countries = Helper::countries();
        $selected = array_keys(PaymentHelper::getAvailableCountries($paymentMethod));

        return $this
            ->add(
                get_payment_setting_key('available_countries_label', $paymentMethod),
                LabelField::class,
                LabelFieldOption::make()
                    ->label(trans('plugins/payment::payment.available_countries'))
                    ->helperText(trans('plugins/payment::payment.available_countries_help'))
                    ->toArray()
            )
            ->add(
                get_payment_setting_key('available_countries_checkall', $paymentMethod),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/payment::payment.all_countries_checkbox'))
                    ->labelAttributes(['class' => 'check-all', 'data-set' => ".$paymentMethod-available-countries"])
                    ->value(array_diff(array_keys($countries), $selected) ? 0 : 1)
                    ->toArray()
            )
            ->add(
                get_payment_setting_key('available_countries[]', $paymentMethod),
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->choices($countries)
                    ->selected($selected)
                    ->attributes(['class' => "$paymentMethod-available-countries"])
                    ->toArray()
            );
    }
}
