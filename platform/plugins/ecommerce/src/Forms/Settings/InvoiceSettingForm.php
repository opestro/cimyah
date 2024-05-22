<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\GoogleFontsField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\Setting\Forms\SettingForm;

class InvoiceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.invoice.company_settings'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.invoice.company_settings_description'))
            ->setValidatorClass(InvoiceSettingRequest::class)
            ->columns(6)
            ->add(
                'company_name_for_invoicing',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.invoice.form.company_name'))
                    ->value(get_ecommerce_setting('company_name_for_invoicing', get_ecommerce_setting('store_name')))
                    ->colspan(6)
                    ->toArray()
            )
            ->add(
                'company_address_for_invoicing',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.invoice.form.company_address'))
                    ->value(get_ecommerce_setting('company_address_for_invoicing', implode(
                        ', ',
                        array_filter([
                            get_ecommerce_setting('store_address'),
                            InvoiceHelper::getCompanyCity(),
                            InvoiceHelper::getCompanyState(),
                            InvoiceHelper::getCompanyCountry(),
                        ]),
                    )))
                    ->colspan(6)
                    ->toArray()
            )
            ->add(
                'company_country_for_invoicing',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::ecommerce.country'))
                    ->choices(EcommerceHelper::getAvailableCountries())
                    ->selected(InvoiceHelper::getCompanyCountry())
                    ->searchable()
                    ->colspan(2)
                    ->toArray(),
            )
            ->add(
                'company_state_for_invoicing',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::ecommerce.state'))
                    ->value(InvoiceHelper::getCompanyState())
                    ->colspan(2)
                    ->toArray()
            )
            ->add(
                'company_city_for_invoicing',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::ecommerce.city'))
                    ->value(InvoiceHelper::getCompanyCity())
                    ->colspan(2)
                    ->toArray()
            )
            ->when(EcommerceHelper::isZipCodeEnabled(), function (FormAbstract $form) {
                $form->add('company_zipcode_for_invoicing', TextField::class, [
                    'label' => trans('plugins/ecommerce::setting.invoice.form.company_zipcode'),
                    'value' => InvoiceHelper::getCompanyZipCode(),
                    'colspan' => 3,
                ]);
            })
            ->add('company_email_for_invoicing', EmailField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.company_email'),
                'value' => get_ecommerce_setting('company_email_for_invoicing') ?: get_ecommerce_setting('store_email'),
                'colspan' => 3,
            ])
            ->add('company_phone_for_invoicing', TextField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.company_phone'),
                'value' => get_ecommerce_setting('company_phone_for_invoicing') ?: get_ecommerce_setting('store_phone'),
                'colspan' => 3,
            ])
            ->add('company_tax_id_for_invoicing', TextField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.company_tax_id'),
                'value' => get_ecommerce_setting('company_tax_id_for_invoicing') ?: get_ecommerce_setting('store_vat_number'),
                'colspan' => 6,
            ])
            ->add('company_logo_for_invoicing', MediaImageField::class, [
                'value' => get_ecommerce_setting('company_logo_for_invoicing') ?: (theme_option('logo_in_invoices') ?: theme_option('logo')),
                'allow_thumb' => false,
                'colspan' => 6,
            ])
            ->add('using_custom_font_for_invoice', OnOffCheckboxField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.using_custom_font_for_invoice'),
                'value' => get_ecommerce_setting('using_custom_font_for_invoice', false),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.custom-font-settings',
                ],
                'colspan' => 6,
            ])
            ->add('open_fieldset_custom_font_settings', HtmlField::class, [
                'html' => sprintf(
                    '<fieldset class="form-fieldset custom-font-settings w-100" style="display: %s;" data-bb-value="1">',
                    get_ecommerce_setting('using_custom_font_for_invoice', false) ? 'block' : 'none'
                ),
            ])
            ->add('invoice_font_family', GoogleFontsField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.invoice_font_family'),
                'selected' => get_ecommerce_setting('invoice_font_family'),
                'colspan' => 6,
            ])
            ->add('close_fieldset_custom_font_settings', HtmlField::class, [
                'html' => '</fieldset>',
            ])
            ->add(
                'invoice_language_support',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.invoice.form.add_language_support'))
                    ->choices([
                        'default' => trans('plugins/ecommerce::setting.invoice.form.only_latin_languages'),
                        'arabic' => 'Arabic',
                        'bangladesh' => 'Bangladesh',
                        'chinese' => 'Chinese',
                    ])
                    ->defaultValue('default')
                    ->when(InvoiceHelper::getLanguageSupport(), function (RadioFieldOption $option, string $language) {
                        $option->selected($language);
                    })
                    ->colspan(6)
                    ->toArray()
            )
            ->add('enable_invoice_stamp', OnOffCheckboxField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.enable_invoice_stamp'),
                'value' => get_ecommerce_setting('enable_invoice_stamp', true),
                'colspan' => 6,
            ])
            ->add('invoice_code_prefix', TextField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.invoice_code_prefix'),
                'value' => get_ecommerce_setting('invoice_code_prefix', 'INV-'),
                'colspan' => 6,
            ])
            ->add('disable_order_invoice_until_order_confirmed', OnOffCheckboxField::class, [
                'label' => trans('plugins/ecommerce::setting.invoice.form.disable_order_invoice_until_order_confirmed'),
                'value' => EcommerceHelper::disableOrderInvoiceUntilOrderConfirmed(),
                'colspan' => 6,
            ])
            ->add(
                'invoice_date_format',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.invoice.form.date_format'))
                    ->choices(array_combine(InvoiceHelper::supportedDateFormats(), InvoiceHelper::supportedDateFormats()))
                    ->selected(get_ecommerce_setting('invoice_date_format', 'F d, Y'))
                    ->searchable()
                    ->colspan(6)
                    ->toArray(),
            );
    }
}
