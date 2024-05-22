<x-core::form.text-input
    :label="trans('core/acl::auth.login.password')"
    type="password"
    name="password"
    :value="BaseHelper::hasDemoModeEnabled() ? config('core.base.general.demo.account.password') : null"
    :placeholder="trans('core/acl::auth.login.placeholder.password')"
    :required="true"
    tabindex="2"
>
    <x-slot:label-description>
        <a
            href="{{ route('access.password.request') }}"
            title="{{ trans('core/acl::auth.forgot_password.title') }}"
            tabindex="5"
        >{{ trans('core/acl::auth.lost_your_password') }}</a>
    </x-slot:label-description>
</x-core::form.text-input>
