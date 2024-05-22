<?php

namespace Botble\SalePopup\Forms\Settings;

use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\FormAbstract;
use Botble\Language\Forms\Fields\LanguageSwitcherField;
use Botble\SalePopup\Http\Requests\Settings\SalePopupSettingRequest;
use Botble\SalePopup\Support\SalePopupHelper;
use Botble\Setting\Forms\SettingForm;

class SalePopupSettingForm extends SettingForm
{
    public function buildForm(): void
    {
        parent::buildForm();

        $salePopupHelper = app(SalePopupHelper::class);

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.sale_popup.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.sale_popup.description'))
            ->setValidatorClass(SalePopupSettingRequest::class)
            ->when($this->getRequest()->isNotFilled('ref_lang'), function (FormAbstract $form) use ($salePopupHelper) {
                $form->add('enabled', 'onOffCheckbox', [
                    'label' => trans('plugins/sale-popup::sale-popup.enable'),
                    'value' => $salePopupHelper->getSetting('enabled', true),
                    'attr' => [
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '#sale-popup-setting',
                    ],
                ]);
            })
            ->add('openWrapper', 'html', [
                'html' => sprintf('<div id="sale-popup-setting" style="display: %s">', $salePopupHelper->getSetting('enabled', true) ? 'block' : 'none'),
            ])
            ->when($this->getRequest()->isNotFilled('ref_lang'), function (FormAbstract $form) use ($salePopupHelper) {
                $form
                    ->add('collection_id', 'select', [
                        'label' => trans('plugins/sale-popup::sale-popup.load_products_from'),
                        'choices' => ['featured_products' => trans('plugins/sale-popup::sale-popup.featured_products')] +
                            get_product_collections()
                                ->pluck('name', 'id')
                                ->toArray(),
                        'value' => $salePopupHelper->getSetting('collection_id'),
                    ])
                    ->add('show_time_ago_suggest', 'onOffCheckbox', [
                        'label' => trans('plugins/sale-popup::sale-popup.show_time_ago_suggest'),
                        'value' => $salePopupHelper->getSetting('show_time_ago_suggest', true),
                    ]);
            })
            ->add('purchased_text', 'text', [
                'label' => trans('plugins/sale-popup::sale-popup.purchased_text'),
                'required' => true,
                'value' => $salePopupHelper->getSetting('purchased_text', 'purchased'),
            ])
            ->add('verified_text', 'text', [
                'label' => trans('plugins/sale-popup::sale-popup.verified_text'),
                'required' => true,
                'value' => $salePopupHelper->getSetting('verified_text', 'Verified'),
            ])
            ->add('quick_view_text', 'text', [
                'label' => trans('plugins/sale-popup::sale-popup.quick_view_text'),
                'required' => true,
                'value' => $salePopupHelper->getSetting('quick_view_text', 'Quick view'),
            ])
            ->add('list_users_purchased', 'textarea', [
                'label' => trans('plugins/sale-popup::sale-popup.list_users_purchased'),
                'required' => true,
                'value' => $salePopupHelper->getSetting(
                    'list_users_purchased',
                    'Nathan (California) | Alex (Texas) | Henry (New York) | Kiti (Ohio) | Daniel (Washington) | Hau (California) | Van (Ohio) | Sara (Montana)  | Kate (Georgia)',
                ),
                'attr' => [
                    'rows' => 3,
                ],
                'help_block' => [
                    'text' => trans('plugins/sale-popup::sale-popup.user_separator'),
                ],
            ])
            ->add('list_sale_time', 'textarea', [
                'label' => trans('plugins/sale-popup::sale-popup.list_sale_time'),
                'required' => true,
                'value' => $salePopupHelper->getSetting(
                    'list_sale_time',
                    '4 hours ago | 2 hours ago | 45 minutes ago | 1 day ago | 8 hours ago | 10 hours ago | 25 minutes ago | 2 day ago | 5 hours ago | 40 minutes ago',
                ),
                'attr' => [
                    'rows' => 3,
                ],
                'help_block' => [
                    'text' => trans('plugins/sale-popup::sale-popup.time_separator'),
                ],
            ])
            ->when($this->getRequest()->isNotFilled('ref_lang'), function (FormAbstract $form) use ($salePopupHelper) {
                $displayPages = json_decode($salePopupHelper->getSetting('display_pages', '["public.index"]'), true);

                $form
                    ->add('limit_products', 'number', [
                        'label' => trans('plugins/sale-popup::sale-popup.limit_products'),
                        'value' => $salePopupHelper->getSetting('limit_products', 20),
                    ])
                    ->add('show_verified', 'onOffCheckbox', [
                        'label' => trans('plugins/sale-popup::sale-popup.show_verified'),
                        'value' => $salePopupHelper->getSetting('show_verified', true),
                    ])
                    ->add('show_close_button', 'onOffCheckbox', [
                        'label' => trans('plugins/sale-popup::sale-popup.show_close_button'),
                        'value' => $salePopupHelper->getSetting('show_close_button', true),
                    ])
                    ->add('show_quick_view_button', 'onOffCheckbox', [
                        'label' => trans('plugins/sale-popup::sale-popup.show_quick_view_button'),
                        'value' => $salePopupHelper->getSetting('show_quick_view_button', true),
                    ])
                    ->add('display_pages[]', MultiCheckListField::class, [
                        'label' => trans('plugins/sale-popup::sale-popup.select_pages_to_display'),
                        'choices' => $salePopupHelper->displayPages(),
                        'value' => old('display_pages', $displayPages),
                        'inline' => true,
                    ]);
            })
            ->add('closeWrapper', 'html', [
                'html' => '</div>',
            ])
            ->when(
                is_plugin_active('language'),
                fn (FormAbstract $form) => $form->add('languageSwitcher', LanguageSwitcherField::class)
            );
    }
}
