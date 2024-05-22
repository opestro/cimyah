<?php

namespace Botble\ACL\Forms\Auth;

use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Models\User;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextField;

class LoginForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(LoginRequest::class)
            ->setUrl(route('access.login'))
            ->heading(trans('core/acl::auth.sign_in_below'))
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::auth.login.username'))
                    ->value(old(
                        'email',
                        BaseHelper::hasDemoModeEnabled() ? config('core.base.general.demo.account.username') : null,
                    ))
                    ->required()
                    ->attributes(['tabindex' => 1, 'placeholder' => trans('core/acl::auth.login.placeholder.username')])
                    ->toArray()
            )
            ->add(
                'password',
                HtmlField::class,
                ['html' => view('core/acl::auth.partials.password')->render()]
            )
            ->add(
                'remember',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('core/acl::auth.login.remember'))
                    ->value(true)
                    ->attributes(['tabindex' => 3])
                    ->toArray()
            )
            ->submitButton(trans('core/acl::auth.login.login'), 'ti ti-login-2')
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, User::class),
            ]);
    }
}
