@if (! $data->confirmed_at)
    <x-core::alert
        type="warning"
    >
        {!! BaseHelper::clean(
        trans('plugins/ecommerce::customer.verify_email.notification', [
            'approve_link' => Html::link(
                route('customers.verify-email', $data->id),
                trans('plugins/ecommerce::customer.verify_email.approve_here'),
                ['class' => 'verify-customer-email-button'],
            ),
        ])) !!}
    </x-core::alert>

    @push('footer')
        <x-core::modal
            id="verify-customer-email-modal"
            type="warning"
            :title="trans('plugins/ecommerce::customer.verify_email.confirm_heading')"
            button-id="confirm-verify-customer-email-button"
            :button-label="trans('plugins/ecommerce::customer.verify_email.confirm_button')"
        >
            {!! trans('plugins/ecommerce::customer.verify_email.confirm_description') !!}
        </x-core::modal>
    @endpush
@endif
