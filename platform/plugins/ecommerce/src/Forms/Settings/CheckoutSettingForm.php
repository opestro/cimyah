<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Helper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\CheckoutSettingRequest;
use Botble\Language\Forms\Fields\LanguageSwitcherField;
use Botble\Setting\Forms\SettingForm;

class CheckoutSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addScriptsDirectly('vendor/core/core/setting/js/setting.js')
            ->addScriptsDirectly('vendor/core/plugins/ecommerce/js/setting.js');

        $countries = Helper::countries();
        $selectedCountries = array_keys(EcommerceHelper::getAvailableCountries());

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.checkout.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.checkout.description'))
            ->setValidatorClass(CheckoutSettingRequest::class)
            ->add('enable_guest_checkout', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.enable_guest_checkout'),
                'value' => EcommerceHelper::isEnabledGuestCheckout(),
            ])
            ->add('minimum_order_amount', 'number', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.minimum_order_amount', ['currency' => get_application_currency()->title]),
                'value' => get_ecommerce_setting('minimum_order_amount', 0),
                'attr' => [
                    'data-thousands-separator' => EcommerceHelper::getThousandSeparatorForInputMask(),
                    'data-decimal-separator' => EcommerceHelper::getDecimalSeparatorForInputMask(),
                    'group-flat' => true,
                ],
            ])
            ->add(
                'minimum_order_quantity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.minimum_order_quantity'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.minimum_order_quantity_helper'))
                    ->value(EcommerceHelper::getMinimumOrderQuantity())
                    ->toArray()
            )
            ->add(
                'maximum_order_quantity',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.checkout.form.maximum_order_quantity'))
                    ->helperText(trans('plugins/ecommerce::setting.checkout.form.maximum_order_quantity_helper'))
                    ->value(EcommerceHelper::getMaximumOrderQuantity())
                    ->toArray()
            )
            ->add('mandatory_form_fields_at_checkout[]', MultiCheckListField::class, [
                'label' => trans('plugins/ecommerce::setting.checkout.form.mandatory_form_fields_at_checkout'),
                'choices' => EcommerceHelper::getMandatoryFieldsAtCheckout(),
                'value' => old('mandatory_form_fields_at_checkout', EcommerceHelper::getEnabledMandatoryFieldsAtCheckout()),
                'inline' => true,
            ])
            ->add('hide_form_fields_at_checkout[]', MultiCheckListField::class, [
                'label' => trans('plugins/ecommerce::setting.checkout.form.hide_form_fields_at_checkout'),
                'choices' => EcommerceHelper::getMandatoryFieldsAtCheckout(),
                'value' => old('hide_form_fields_at_checkout', EcommerceHelper::getHiddenFieldsAtCheckout()),
                'inline' => true,
            ])
            ->add('zip_code_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.zip_code_enabled'),
                'value' => EcommerceHelper::isZipCodeEnabled(),
            ])
            ->add('billing_address_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.billing_address_enabled'),
                'value' => EcommerceHelper::isBillingAddressEnabled(),
            ])
            ->add('display_tax_fields_at_checkout_page', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.display_tax_fields_at_checkout_page'),
                'value' => EcommerceHelper::isDisplayTaxFieldsAtCheckoutPage(),
            ])
            ->when(is_plugin_active('location'), function () use ($countries, $selectedCountries) {
                $this
                    ->add('load_countries_states_cities_from_location_plugin', 'customRadio', [
                        'label' => trans('plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin'),
                        'value' => $loadLocationFromPlugin = (bool) get_ecommerce_setting('load_countries_states_cities_from_location_plugin', 0),
                        'values' => [
                            0 => trans('core/base::base.no'),
                            1 => trans('core/base::base.yes'),
                        ],
                        'help_block' => [
                            'text' => trans(
                                'plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin_placeholder',
                            ) . ' ' . trans(
                                'plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin_placeholder_2',
                            ),
                        ],
                        'attr' => [
                            'data-bb-toggle' => 'collapse',
                            'data-bb-target' => '.location-settings',
                        ],
                    ])
                    ->add('open_fieldset_location_settings', 'html', [
                        'html' => '<fieldset class="form-fieldset"/>',
                    ])
                    ->add('open_wrapper_use_city_field', 'html', [
                        'html' => sprintf('<div class="location-settings" data-bb-value="1" style="display: %s">', $loadLocationFromPlugin ? 'block' : 'none'),
                    ])
                    ->add('use_city_field_as_field_text', 'onOffCheckbox', [
                        'label' => trans('plugins/ecommerce::setting.checkout.form.use_city_field_as_field_text'),
                        'value' => get_ecommerce_setting('use_city_field_as_field_text', false),
                    ])
                    ->add('close_wrapper_use_city_field', 'html', [
                        'html' => '</div>',
                    ])
                    ->add('open_location_settings', 'html', [
                        'html' => sprintf(
                            '<div class="form-group location-settings" style="display: %s;" data-bb-value="0">',
                            ! $loadLocationFromPlugin ? 'block' : 'none',
                        ),
                    ])
                    ->add(
                        'location_settings_label',
                        LabelField::class,
                        LabelFieldOption::make()->label(trans('plugins/ecommerce::setting.checkout.form.available_countries'))->toArray()
                    )
                    ->add('available_countries_all', 'onOffCheckbox', [
                        'label' => trans('plugins/ecommerce::setting.checkout.form.all'),
                        'label_attr' => [
                            'class' => 'check-all',
                            'data-set' => '.available-countries',
                        ],
                        'help_block' => [
                            'text' => trans('plugins/ecommerce::setting.checkout.form.all_helper_text'),
                        ],
                        'value' => (count($selectedCountries) - 1) == count($countries) ? '1' : '',
                    ])
                    ->add('available_countries[]', MultiCheckListField::class, [
                        'label' => false,
                        'choices' => $countries,
                        'value' => $selectedCountries,
                        'attr' => [
                            'class' => 'available-countries',
                        ],
                    ])
                    ->add('close_location_settings', 'html', [
                        'html' => '</div>',
                    ])
                    ->add('close_fieldset_location_settings', 'html', [
                        'html' => '</fieldset>',
                    ]);
            }, function () use ($countries, $selectedCountries) {
                $this
                    ->add(
                        'location_settings_label',
                        LabelField::class,
                        LabelFieldOption::make()->label(trans('plugins/ecommerce::setting.checkout.form.available_countries'))->toArray()
                    )
                    ->add('open_fieldset_location_settings', 'html', [
                        'html' => '<fieldset class="form-fieldset"/>',
                    ])
                    ->add('available_countries_all', 'onOffCheckbox', [
                        'label' => trans('plugins/ecommerce::setting.checkout.form.all'),
                        'label_attr' => [
                            'class' => 'check-all',
                            'data-set' => '.available-countries',
                        ],
                        'help_block' => [
                            'text' => trans('plugins/ecommerce::setting.checkout.form.all_helper_text'),
                        ],
                        'value' => (count($selectedCountries) - 1) == count($countries) ? '1' : '',
                    ])
                    ->add('available_countries[]', MultiCheckListField::class, [
                        'label' => false,
                        'choices' => $countries,
                        'value' => $selectedCountries,
                        'attr' => [
                            'class' => 'available-countries',
                        ],
                    ])
                    ->add('close_fieldset_location_settings', 'html', [
                        'html' => '</fieldset>',
                    ]);
            })
            ->add('enable_customer_recently_viewed_products', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.recently_viewed.enable'),
                'value' => EcommerceHelper::isEnabledCustomerRecentlyViewedProducts(),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.recently-viewed-products-settings',
                ],
            ])
            ->add('open_fieldset_recently_viewed_products_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset recently-viewed-products-settings" style="display: %s;" data-bb-value="1">',
                    EcommerceHelper::isEnabledCustomerRecentlyViewedProducts() ? 'block' : 'none',
                ),
            ])
            ->add('max_customer_recently_viewed_products', 'number', [
                'label' => trans('plugins/ecommerce::setting.checkout.form.recently_viewed.max'),
                'value' => EcommerceHelper::maxCustomerRecentlyViewedProducts(),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.checkout.form.recently_viewed.max_helper'),
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('close_fieldset_recently_viewed_products_settings', 'html', [
                'html' => '</fieldset>',
            ])
            ->when(is_plugin_active('language'), function (FormAbstract $form) {
                $form->add('languageSwitcher', LanguageSwitcherField::class);
            });
    }
}
