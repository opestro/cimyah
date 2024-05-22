<?php

namespace Botble\Ecommerce\Forms\Fronts\Auth;

use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Botble\Ecommerce\Models\Customer;

class ChangePasswordForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setUrl(route('customer.post.change-password'))
            ->setupModel(new Customer())
            ->setValidatorClass(UpdatePasswordRequest::class)
            ->contentOnly()
            ->add(
                'old_password',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('Current password'))
                    ->label(__('Current password'))
                    ->required()
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('New password'))
                    ->label(__('Password'))
                    ->required()
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->placeholder(__('Confirm password'))
                    ->label(__('Password confirmation'))
                    ->required()
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->label(__('Change password'))
                    ->cssClass('btn btn-primary')
            );
    }
}
