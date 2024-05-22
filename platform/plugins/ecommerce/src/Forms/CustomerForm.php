<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Http\Requests\CustomerCreateRequest;
use Botble\Ecommerce\Models\Customer;

class CustomerForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/address.js')
            ->addScriptsDirectly('vendor/core/plugins/location/js/location.js')
            ->addStylesDirectly('vendor/core/plugins/ecommerce/css/customer-admin.css')
            ->addStylesDirectly('vendor/core/plugins/ecommerce/css/review.css');

        $this
            ->setupModel(new Customer())
            ->setValidatorClass(CustomerCreateRequest::class)
            ->template('plugins/ecommerce::customers.form')
            ->add('name', TextField::class, NameFieldOption::make()->maxLength(120)->toArray())
            ->add('email', TextField::class, EmailFieldOption::make()->required()->colspan(2)->toArray())
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::customer.phone'))
                    ->placeholder(trans('plugins/ecommerce::customer.phone_placeholder'))
                    ->maxLength(15)
                    ->toArray()
            )
            ->add(
                'dob',
                DatePickerField::class,
                DatePickerFieldOption::make()->label(trans('plugins/ecommerce::customer.dob'))->toArray()
            )
            ->add(
                'is_change_password',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::customer.change_password'))
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '#password-collapse',
                    ])
                    ->defaultValue(0)
                    ->toArray()
            )
            ->add('openRow1', 'html', [
                'html' => '<div class="row" id="password-collapse" data-bb-value="1"' . ($this->getModel()->id ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'password',
                'password',
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::customer.password'))
                    ->required()
                    ->maxLength(60)
                    ->wrapperAttributes([
                        'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                    ])
                    ->toArray()
            )
            ->add(
                'password_confirmation',
                'password',
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::customer.password_confirmation'))
                    ->required()
                    ->maxLength(60)
                    ->wrapperAttributes([
                        'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                    ])
                    ->toArray()
            )
            ->add('closeRow1', 'html', [
                'html' => '</div>',
            ])
            ->add(
                'private_notes',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/ecommerce::customer.private_notes'))
                    ->helperText(trans('plugins/ecommerce::customer.private_notes_helper'))
                    ->rows(2)
                    ->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(CustomerStatusEnum::labels())->toArray())
            ->add('avatar', MediaImageField::class)
            ->setBreakFieldPoint('status')
            ->when($this->getModel()->getKey(), function () {
                $wishlist = $this->getModel()->wishlist->loadMissing('product');

                $this
                    ->addMetaBoxes([
                        'addresses' => [
                            'title' => trans('plugins/ecommerce::addresses.addresses'),
                            'content' => view('plugins/ecommerce::customers.addresses.addresses', [
                                'addresses' => $this->model->addresses()->get(),
                            ])->render(),
                            'header_actions' => view('plugins/ecommerce::customers.addresses.address-actions')->render(),
                            'wrap' => true,
                            'has_table' => true,
                        ],
                        'wishlist' => [
                            'title' => trans('plugins/ecommerce::ecommerce.wishlist'),
                            'content' => view('plugins/ecommerce::customers.wishlist', compact('wishlist'))->render(),
                            'wrap' => true,
                            'has_table' => true,
                        ],
                        'payments' => [
                            'title' => trans('plugins/ecommerce::payment.name'),
                            'content' => view('plugins/ecommerce::customers.payments.payments', [
                                'payments' => $this->model->payments()->get(),
                            ])->render(),
                            'wrap' => true,
                            'has_table' => true,
                        ],
                    ]);
            });
    }
}
