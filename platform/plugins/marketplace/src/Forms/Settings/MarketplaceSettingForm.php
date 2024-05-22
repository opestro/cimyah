<?php

namespace Botble\Marketplace\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\MarketPlaceSettingFormRequest;
use Botble\Marketplace\Models\Store;
use Botble\Setting\Forms\SettingForm;

class MarketplaceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
                'vendor/core/plugins/marketplace/js/marketplace-setting.js',
            ]);

        $commissionEachCategory = [];

        if (MarketplaceHelper::isCommissionCategoryFeeBasedEnabled()) {
            $commissionEachCategory = Store::getCommissionEachCategory();
        }

        $this
            ->setSectionTitle(trans('plugins/marketplace::marketplace.settings.title'))
            ->setSectionDescription(trans('plugins/marketplace::marketplace.settings.description'))
            ->setValidatorClass(MarketPlaceSettingFormRequest::class)
            ->contentOnly()
            ->add('fee_per_order', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.default_commission_fee'),
                'value' => MarketplaceHelper::getSetting('fee_per_order', 0),
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('enable_commission_fee_for_each_category', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.enable_commission_fee_for_each_category'),
                'value' => MarketplaceHelper::isCommissionCategoryFeeBasedEnabled(),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.category-commission-fee-settings',
                ],
            ])
            ->add('category_commission_fee_fields', 'html', [
                'html' => view(
                    'plugins/marketplace::settings.partials.category-commission-fee-fields',
                    compact('commissionEachCategory')
                )->render(),
            ])
            ->add('fee_withdrawal', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.fee_withdrawal'),
                'value' => MarketplaceHelper::getSetting('fee_withdrawal', 0),
            ])
            ->add('check_valid_signature', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.check_valid_signature'),
                'value' => MarketplaceHelper::getSetting('check_valid_signature', true),
            ])
            ->add('verify_vendor', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.verify_vendor'),
                'value' => MarketplaceHelper::getSetting('verify_vendor', true),
            ])
            ->add(
                'enable_product_approval',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.enable_product_approval'))
                    ->value(MarketplaceHelper::getSetting('enable_product_approval', true))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.enable_product_approval_description'))
                    ->toArray()
            )
            ->add('max_filesize_upload_by_vendor', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.max_upload_filesize'),
                'value' => $maxSize = MarketplaceHelper::maxFilesizeUploadByVendor(),
                'attr' => [
                    'placeholder' => trans(
                        'plugins/marketplace::marketplace.settings.max_upload_filesize_placeholder',
                        [
                            'size' => $maxSize,
                        ]
                    ),
                    'step' => 1,
                ],
            ])
            ->add('max_product_images_upload_by_vendor', 'number', [
                'label' => trans('plugins/marketplace::marketplace.settings.max_product_images_upload_by_vendor'),
                'value' => MarketplaceHelper::maxProductImagesUploadByVendor(),
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add('enabled_vendor_registration', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.enable_vendor_registration'),
                'value' => MarketplaceHelper::isVendorRegistrationEnabled(),
            ])
            ->add(
                'hide_become_vendor_menu_in_customer_dashboard',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.hide_become_vendor_menu_in_customer_dashboard'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.hide_become_vendor_menu_in_customer_dashboard_description'))
                    ->value(MarketplaceHelper::getSetting('hide_become_vendor_menu_in_customer_dashboard', false))
                    ->toArray()
            )
            ->add('hide_store_phone_number', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_phone_number'),
                'value' => MarketplaceHelper::hideStorePhoneNumber(),
            ])
            ->add('hide_store_email', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_email'),
                'value' => MarketplaceHelper::hideStoreEmail(),
            ])
            ->add('hide_store_address', OnOffCheckboxField::class, [
                'label' => trans('plugins/marketplace::marketplace.settings.hide_store_address'),
                'value' => MarketplaceHelper::hideStoreAddress(),
            ])
            ->add(
                'hide_store_social_links',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.hide_store_social_links'))
                    ->value(MarketplaceHelper::hideStoreSocialLinks())
                    ->toArray()
            )
            ->add(
                'allow_vendor_manage_shipping',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.allow_vendor_manage_shipping'))
                    ->value(MarketplaceHelper::allowVendorManageShipping())
                    ->helperText(trans('plugins/marketplace::marketplace.settings.allow_vendor_manage_shipping_description'))
                    ->toArray()
            )
            ->add('payment_method_fields', 'html', [
                'html' => view('plugins/marketplace::settings.partials.payment-method-fields')->render(),
            ])
            ->add(
                'minimum_withdrawal_amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.minimum_withdrawal_amount'))
                    ->helperText(trans('plugins/marketplace::marketplace.settings.minimum_withdrawal_amount_helper'))
                    ->value(MarketplaceHelper::getMinimumWithdrawalAmount())
                    ->toArray(),
            )
            ->add(
                'allow_vendor_delete_their_orders',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/marketplace::marketplace.settings.allow_vendor_delete_their_orders'))
                    ->helperText(
                        trans('plugins/marketplace::marketplace.settings.allow_vendor_delete_their_orders_description')
                    )
                    ->value(MarketplaceHelper::allowVendorDeleteTheirOrders())
                    ->toArray()
            );
    }
}
