<?php

namespace Botble\Ecommerce\PanelSections;

use Botble\Base\PanelSections\PanelSection;
use Botble\Base\PanelSections\PanelSectionItem;

class SettingEcommercePanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('settings.ecommerce')
            ->setTitle(trans('plugins/ecommerce::ecommerce.name'))
            ->withPriority(1000)
            ->addItems([
                PanelSectionItem::make('settings.ecommerce.general_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.general.name'))
                    ->withIcon('ti ti-settings')
                    ->withDescription(trans('plugins/ecommerce::setting.general.description'))
                    ->withPriority(10)
                    ->withRoute('ecommerce.settings.general'),
                PanelSectionItem::make('settings.ecommerce.currency_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.currency.name'))
                    ->withIcon('ti ti-coin')
                    ->withPriority(20)
                    ->withDescription(trans('plugins/ecommerce::setting.currency.description'))
                    ->withRoute('ecommerce.settings.currencies'),
                PanelSectionItem::make('settings.ecommerce.store-locator-settings')
                    ->setTitle(trans('plugins/ecommerce::setting.store_locator.name'))
                    ->withIcon('ti ti-map-pin')
                    ->withDescription(trans('plugins/ecommerce::setting.store_locator.description'))
                    ->withPriority(30)
                    ->withRoute('ecommerce.settings.store-locators'),
                PanelSectionItem::make('settings.ecommerce.product_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.product.name'))
                    ->withIcon('ti ti-packages')
                    ->withDescription(trans('plugins/ecommerce::setting.product.description'))
                    ->withPriority(40)
                    ->withRoute('ecommerce.settings.products'),
                PanelSectionItem::make('settings.ecommerce.product_search_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.product_search.name'))
                    ->withIcon('ti ti-search')
                    ->withDescription(trans('plugins/ecommerce::setting.product_search.description'))
                    ->withPriority(50)
                    ->withRoute('ecommerce.settings.product-search'),
                PanelSectionItem::make('settings.ecommerce.digital_product_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.digital_product.name'))
                    ->withIcon('ti ti-device-desktop')
                    ->withDescription(trans('plugins/ecommerce::setting.digital_product.description'))
                    ->withPriority(60)
                    ->withRoute('ecommerce.settings.digital-products'),
                PanelSectionItem::make('settings.ecommerce.product_review_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.product_review.name'))
                    ->withIcon('ti ti-star')
                    ->withDescription(trans('plugins/ecommerce::setting.product_review.description'))
                    ->withPriority(70)
                    ->withRoute('ecommerce.settings.product-reviews'),
                PanelSectionItem::make('settings.ecommerce.shopping_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.shopping.name'))
                    ->withIcon('ti ti-shopping-cart')
                    ->withDescription(trans('plugins/ecommerce::setting.shopping.description'))
                    ->withPriority(80)
                    ->withRoute('ecommerce.settings.shopping'),
                PanelSectionItem::make('settings.ecommerce.checkout_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.checkout.name'))
                    ->withIcon('ti ti-shopping-cart-share')
                    ->withDescription(trans('plugins/ecommerce::setting.checkout.panel_description'))
                    ->withPriority(90)
                    ->withRoute('ecommerce.settings.checkout'),
                PanelSectionItem::make('settings.ecommerce.return_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.return.name'))
                    ->withIcon('ti ti-receipt-refund')
                    ->withDescription(trans('plugins/ecommerce::setting.return.panel_description'))
                    ->withPriority(100)
                    ->withRoute('ecommerce.settings.return'),
                PanelSectionItem::make('settings.ecommerce.invoice_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.invoice.name'))
                    ->withIcon('ti ti-file-invoice')
                    ->withDescription(trans('plugins/ecommerce::setting.invoice.description'))
                    ->withPriority(110)
                    ->withRoute('ecommerce.settings.invoices'),
                PanelSectionItem::make('settings.ecommerce.invoice_template_settings')
                    ->setTitle(trans('plugins/ecommerce::invoice-template.name'))
                    ->withIcon('ti ti-list-details')
                    ->withDescription(trans('plugins/ecommerce::invoice-template.setting_description'))
                    ->withPriority(120)
                    ->withRoute('ecommerce.settings.invoice-template'),
                PanelSectionItem::make('settings.ecommerce.tax_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.tax.name'))
                    ->withIcon('ti ti-receipt-tax')
                    ->withDescription(trans('plugins/ecommerce::setting.tax.description'))
                    ->withPriority(130)
                    ->withRoute('ecommerce.settings.taxes'),
                PanelSectionItem::make('settings.ecommerce.customer_settings')
                    ->setTitle(trans('plugins/ecommerce::setting.customer.name'))
                    ->withIcon('ti ti-users')
                    ->withDescription(trans('plugins/ecommerce::setting.customer.description'))
                    ->withPriority(140)
                    ->withRoute('ecommerce.settings.customers'),
                PanelSectionItem::make('settings.ecommerce.shipping')
                    ->setTitle(trans('plugins/ecommerce::setting.shipping.name'))
                    ->withIcon('ti ti-cube-send')
                    ->withDescription(trans('plugins/ecommerce::setting.shipping.description'))
                    ->withPriority(150)
                    ->withRoute('ecommerce.settings.shipping'),
                PanelSectionItem::make('settings.ecommerce.webhook')
                    ->setTitle(trans('plugins/ecommerce::setting.webhook.name'))
                    ->withIcon('ti ti-webhook')
                    ->withDescription(trans('plugins/ecommerce::setting.webhook.description'))
                    ->withPriority(160)
                    ->withRoute('ecommerce.settings.webhook'),
                PanelSectionItem::make('settings.ecommerce.tracking_setting')
                    ->setTitle(trans('plugins/ecommerce::setting.tracking.name'))
                    ->withIcon('ti ti-robot-face')
                    ->withDescription(trans('plugins/ecommerce::setting.tracking.description'))
                    ->withPriority(170)
                    ->withRoute('ecommerce.settings.tracking'),
                PanelSectionItem::make('settings.ecommerce.standard_and_format')
                    ->setTitle(trans('plugins/ecommerce::setting.standard_and_format.name'))
                    ->withIcon('ti ti-checklist')
                    ->withDescription(trans('plugins/ecommerce::setting.standard_and_format.panel_description'))
                    ->withPriority(180)
                    ->withRoute('ecommerce.settings.standard-and-format'),
                PanelSectionItem::make('settings.ecommerce.flash_sale')
                    ->setTitle(trans('plugins/ecommerce::setting.flash_sale.name'))
                    ->withIcon('ti ti-speakerphone')
                    ->withDescription(trans('plugins/ecommerce::setting.flash_sale.description'))
                    ->withPriority(190)
                    ->withRoute('ecommerce.settings.flash-sale'),
            ]);
    }
}
