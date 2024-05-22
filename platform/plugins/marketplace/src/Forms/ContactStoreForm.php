<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Marketplace\Http\Requests\Fronts\ContactStoreRequest;
use Botble\Theme\FormFront;

class ContactStoreForm extends FormFront
{
    public static function formTitle(): string
    {
        return trans('plugins/marketplace::marketplace.contact_store.form_name');
    }

    public function setup(): void
    {
        $customer = auth('customer')->user();

        $this
            ->contentOnly()
            ->setUrl(route('public.ajax.stores.contact', $this->getModel()['id']))
            ->setValidatorClass(ContactStoreRequest::class)
            ->setFormOption('class', 'bb-contact-store-form')
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(false)
                    ->placeholder(__('Your name'))
                    ->disabled((bool) $customer?->name)
                    ->value($customer?->name),
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(false)
                    ->placeholder(__('Your email address'))
                    ->disabled((bool) $customer?->email)
                    ->value($customer?->email),
            )
            ->add(
                'content',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(false)
                    ->placeholder(__('Type your message...'))
                    ->rows(5)
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Send message'))
                    ->attributes(['class' => 'btn btn-primary'])
            );
    }
}
