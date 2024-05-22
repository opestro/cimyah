<?php

namespace Botble\Ecommerce\Forms\Fronts\Auth;

use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Ecommerce\Http\Requests\Fronts\Auth\ForgotPasswordRequest;

class ForgotPasswordForm extends AuthForm
{
    public static function formTitle(): string
    {
        return __('Customer forgot password form');
    }

    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.password.request'))
            ->setValidatorClass(ForgotPasswordRequest::class)
            ->icon('ti ti-lock-question')
            ->heading(__('Forgot Password'))
            ->description(__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.'))
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->placeholder(__('Email address'))
                    ->icon('ti ti-mail')
                    ->toArray()
            )
            ->submitButton(__('Send Password Reset Link'))
            ->add('back_to_login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center"><a href="%s" class="text-decoration-underline">%s</a></div>',
                    route('customer.login'),
                    __('Back to login page')
                ),
            ]);
    }
}
