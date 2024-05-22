<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Forms\Concerns\HasSubmitButton;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Models\Store;

class StoreForm extends FormAbstract
{
    use HasSubmitButton;

    public function setup(): void
    {
        Assets::addScriptsDirectly([
            'vendor/core/plugins/location/js/location.js',
            'vendor/core/plugins/marketplace/js/store.js',
        ]);

        $this
            ->setupModel(new Store())
            ->setValidatorClass(StoreRequest::class)
            ->columns(6)
            ->contentOnly()
            ->hasFiles()
            ->add('name', TextField::class, NameFieldOption::make()->required()->colspan(6)->toArray())
            ->add('slug', 'html', [
                'html' => view('plugins/marketplace::stores.partials.shop-url-field', ['store' => $this->getModel()])->render(),
                'colspan' => 3,
            ])
            ->add('email', EmailField::class, EmailFieldOption::make()->required()->colspan(3)->toArray())
            ->add('phone', TextField::class, [
                'label' => trans('plugins/marketplace::store.forms.phone'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/marketplace::store.forms.phone_placeholder'),
                    'data-counter' => 15,
                ],
                'colspan' => 6,
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->colspan(6)->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make()->colspan(6)->toArray())
            ->when(EcommerceHelper::isUsingInMultipleCountries(), function (FormAbstract $form) {
                $form->add('country', SelectField::class, [
                    'label' => trans('plugins/marketplace::store.forms.country'),
                    'attr' => [
                        'data-type' => 'country',
                    ],
                    'colspan' => 2,
                    'choices' => EcommerceHelper::getAvailableCountries(),
                    'selected' => old('country', $this->getModel()->country),
                ]);
            })
            ->when(EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation(), function (FormAbstract $form) {
                $form
                    ->add('state', SelectField::class, [
                        'label' => trans('plugins/location::city.state'),
                        'attr' => [
                            'class' => 'select-search-full',
                            'data-type' => 'state',
                            'data-url' => route('ajax.states-by-country'),
                        ],
                        'colspan' => 2,
                        'choices' => ['' => trans('plugins/location::city.select_state')] + EcommerceHelper::getAvailableStatesByCountry(old('country', $this->getModel()->country)),
                    ])
                    ->add('city', SelectField::class, [
                        'label' => trans('plugins/location::city.city'),
                        'attr' => [
                            'class' => 'select-search-full',
                            'data-type' => 'city',
                            'data-url' => route('ajax.cities-by-state'),
                        ],
                        'colspan' => 2,
                        'choices' => ['' => trans('plugins/location::city.select_city')] + EcommerceHelper::getAvailableCitiesByState(old('state', $this->getModel()->state)),
                    ]);
            })
            ->when(! EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation(), function (FormAbstract $form) {
                $form
                    ->add('state', TextField::class, [
                        'label' => trans('plugins/ecommerce::shipping.rule.item.forms.state'),
                        'attr' => [
                            'placeholder' => trans('plugins/ecommerce::shipping.rule.item.forms.state_placeholder'),
                        ],
                        'colspan' => 2,
                    ])
                    ->add('city', TextField::class, [
                        'label' => trans('plugins/ecommerce::shipping.rule.item.forms.city'),
                        'attr' => [
                            'placeholder' => trans('plugins/ecommerce::shipping.rule.item.forms.city_placeholder'),
                        ],
                        'colspan' => 2,
                    ]);
            })
            ->when($zipCodeEnabled = EcommerceHelper::isZipCodeEnabled(), function (FormAbstract $form) {
                $form->add('zip_code', TextField::class, [
                    'label' => trans('plugins/marketplace::store.forms.zip_code'),
                    'attr' => [
                        'placeholder' => trans('plugins/marketplace::store.forms.zip_code_placeholder'),
                        'data-counter' => 120,
                    ],
                    'colspan' => 2,
                ]);
            })
            ->add('address', TextField::class, [
                'label' => trans('plugins/marketplace::store.forms.address'),
                'attr' => [
                    'placeholder' => trans('plugins/marketplace::store.forms.address_placeholder'),
                    'data-counter' => 120,
                ],
                'colspan' => $zipCodeEnabled ? 2 : 3,
            ])
            ->add('company', TextField::class, [
                'label' => trans('plugins/marketplace::store.forms.company'),
                'attr' => [
                    'placeholder' => trans('plugins/marketplace::store.forms.company_placeholder'),
                    'data-counter' => 255,
                ],
                'colspan' => $zipCodeEnabled ? 2 : 3,
            ])
            ->add('logo', 'mediaImage', [
                'label' => trans('plugins/marketplace::store.forms.logo'),
                'colspan' => 2,
            ])
            ->add(
                'cover_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Cover Image'))
                    ->colspan(2)
                    ->toArray()
            )
            ->add('status', SelectField::class, [
                'label' => trans('core/base::tables.status'),
                'required' => true,
                'choices' => BaseStatusEnum::labels(),
                'help_block' => [
                    TextField::class => trans('plugins/marketplace::marketplace.helpers.store_status', [
                        'customer' => CustomerStatusEnum::LOCKED()->label(),
                        'status' => BaseStatusEnum::PUBLISHED()->label(),
                    ]),
                ],
                'colspan' => 3,
            ])
            ->add('customer_id', SelectField::class, [
                'label' => trans('plugins/marketplace::store.forms.store_owner'),
                'required' => true,
                'choices' => [0 => trans('plugins/marketplace::store.forms.select_store_owner')] + Customer::query()
                    ->where('is_vendor', true)
                    ->pluck('name', 'id')
                    ->all(),
                'colspan' => 3,
            ])
            ->addSubmitButton(trans('core/base::forms.save_and_continue'), attributes: ['colspan' => 6]);
    }
}
