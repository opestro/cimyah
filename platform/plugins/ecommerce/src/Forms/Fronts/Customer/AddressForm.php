<?php

namespace Botble\Ecommerce\Forms\Fronts\Customer;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\InputFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Models\Address;

class AddressForm extends FormAbstract
{
    protected string $formSelectInputClass;

    public function setup(): void
    {
        $model = $this->getModel();

        $this
            ->setupModel(new Address())
            ->setValidatorClass(AddressRequest::class)
            ->contentOnly()
            ->columns()
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::addresses.name'))
                    ->placeholder(trans('plugins/ecommerce::addresses.name_placeholder'))
                    ->colspan(1)
                    ->toArray()
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::addresses.phone'))
                    ->placeholder(trans('plugins/ecommerce::addresses.phone_placeholder'))
                    ->colspan(1)
                    ->toArray()
            )
            ->add('email', EmailField::class, EmailFieldOption::make()->colspan(1)->toArray())
            ->add(
                'zip_code',
                TextField::class,
                TextFieldOption::make()
                    ->placeholder(trans('plugins/ecommerce::addresses.zip_placeholder'))
                    ->label(trans('plugins/ecommerce::addresses.zip'))
                    ->colspan(1)
                    ->toArray()
            )
            ->add(
                'address',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::addresses.address'))
                    ->placeholder(trans('plugins/ecommerce::addresses.address_placeholder'))
                    ->colspan(2)
                    ->toArray()
            )
            ->when(EcommerceHelper::isUsingInMultipleCountries(), function (AddressForm $form) {
                $form->add(
                    'country',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('plugins/ecommerce::addresses.country'))
                        ->attributes([
                            'data-type' => 'country',
                        ])
                        ->choices(EcommerceHelper::getAvailableCountries())
                        ->colspan(2)
                        ->toArray()
                );
            }, function (AddressForm $form) {
                $form->add(
                    'country',
                    'hidden',
                    InputFieldOption::make()
                        ->value(EcommerceHelper::getFirstCountryId())
                        ->toArray()
                );
            })
            ->when(EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation(), function (AddressForm $form) {
                $model = $this->getModel();

                $form->add(
                    'state',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->choices(
                            ['' => __('Select state...')] + EcommerceHelper::getAvailableStatesByCountry(
                                old('country', $model->country) ?: EcommerceHelper::getFirstCountryId()
                            )
                        )
                        ->attributes([
                            'data-type' => 'state',
                            'data-url' => route('ajax.states-by-country'),
                        ])
                        ->colspan(1)
                        ->label(trans('plugins/ecommerce::addresses.state'))
                        ->toArray()
                );
            }, function (AddressForm $form) {
                $form->add(
                    'state',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(trans('plugins/ecommerce::addresses.state'))
                        ->colspan(1)
                        ->toArray()
                );
            })
            ->when(EcommerceHelper::useCityFieldAsTextField(), function (AddressForm $form) {
                $form->add(
                    'city',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(trans('plugins/ecommerce::addresses.city'))
                        ->colspan(1)
                        ->toArray()
                );
            }, function (AddressForm $form) {
                $form->add(
                    'city',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('plugins/ecommerce::addresses.city'))
                        ->attributes([
                            'data-type' => 'city',
                            'data-url' => route('ajax.cities-by-state'),
                        ])
                        ->colspan(1)
                        ->choices(
                            ['' => __('Select city...')] + EcommerceHelper::getAvailableCitiesByState(
                                old('state', $form->getModel()->state)
                            )
                        )
                    ->toArray()
                );
            })
            ->add(
                'is_default',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Use this address as default.'))
                    ->checked($model && $model->is_default)
                    ->colspan(2)
                    ->toArray()
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->colspan(2)
                    ->label(($model && $model->getKey()) ? __('Update') : __('Create'))
                    ->cssClass('btn btn-primary mt-2')
                    ->toArray()
            );
    }

    public function setFormSelectInputClass(string $cssClass): static
    {
        $this->formSelectInputClass = $cssClass;

        return $this;
    }

    public function renderForm(
        array $options = [],
        bool $showStart = true,
        bool $showFields = true,
        bool $showEnd = true
    ): string {
        foreach ($this->getFields() as &$field) {
            if ($field->getType() != SelectField::class) {
                continue;
            }

            if (isset($this->formSelectClass)) {
                $field->setOption('attr.class', $this->formSelectClass);
            }
        }

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }
}
