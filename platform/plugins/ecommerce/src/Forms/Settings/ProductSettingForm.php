<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\Fields\OnOffField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ProductSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ProductSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.product.product_settings'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.product.product_settings_description'))
            ->setValidatorClass(ProductSettingRequest::class)
            ->add('how_to_display_product_variation_images', 'customRadio', [
                'label' => trans('plugins/ecommerce::setting.product.form.how_to_display_product_variation_images'),
                'values' => [
                    'only_variation_images' => trans('plugins/ecommerce::setting.product.form.only_variation_images'),
                    'variation_images_and_main_product_images' => trans(
                        'plugins/ecommerce::setting.product.form.variation_images_and_main_product_images',
                    ),
                ],
                'value' => get_ecommerce_setting('how_to_display_product_variation_images', 'only_variation_images'),
            ])
            ->add('show_number_of_products', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.show_number_of_products'),
                'value' => EcommerceHelper::showNumberOfProductsInProductSingle(),
            ])
            ->add('show_out_of_stock_products', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.show_out_of_stock_products'),
                'value' => EcommerceHelper::showOutOfStockProducts(),
            ])
            ->add('is_enabled_product_options', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.enable_product_options'),
                'value' => EcommerceHelper::isEnabledProductOptions(),
            ])
            ->add('is_enabled_cross_sale_products', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.is_enabled_cross_sale_products'),
                'value' => EcommerceHelper::isEnabledCrossSaleProducts(),
            ])
            ->add('is_enabled_related_products', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.is_enabled_related_products'),
                'value' => EcommerceHelper::isEnabledRelatedProducts(),
            ])
            ->add('auto_generate_product_sku', OnOffField::class, [
                'label' => trans('plugins/ecommerce::setting.product.form.auto_generate_product_sku'),
                'value' => get_ecommerce_setting('auto_generate_product_sku', true),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.auto-generate-sku-settings',
                ],
            ])
            ->add('open_fieldset_product_sku_format', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset auto-generate-sku-settings" style="display: %s;" data-bb-value="1">',
                    get_ecommerce_setting('auto_generate_product_sku', true) ? 'block' : 'none'
                ),
            ])
            ->add('product_sku_format', 'text', [
                'label' => trans('plugins/ecommerce::setting.product.form.product_sku_format'),
                'value' => get_ecommerce_setting('product_sku_format', null),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.product.form.product_sku_format_helper'),
                ],
            ])
            ->add('close_fieldset_product_sku_format', 'html', [
                'html' => '</fieldset>',
            ]);
    }
}
