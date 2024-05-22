<x-plugins-payment::settings-card
    name="Mollie"
    :id="MOLLIE_PAYMENT_METHOD_NAME"
    :logo="url('vendor/core/plugins/mollie/images/mollie.png')"
    url="https://mollie.com"
    :description="__('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Mollie'])"
>
    <x-slot:instructions>
        <ol>
            <li>
                <p>
                    <a
                        href="https://www.mollie.com/dashboard/signup"
                        target="_blank"
                    >
                        {{ __('Register an account on :name', ['name' => 'Mollie']) }}
                    </a>
                </p>
            </li>
            <li>
                <p>
                    {{ __('After registration at :name, you will have API key', ['name' => 'Mollie']) }}
                </p>
            </li>
            <li>
                <p>{{ __('Enter API key into the box in right hand') }}</p>
            </li>
        </ol>
    </x-slot:instructions>

    <x-slot:fields>
        <x-core::form.text-input
            :label="__('API Key')"
            :name="sprintf('payment_%s_api_key', MOLLIE_PAYMENT_METHOD_NAME)"
            :value="get_payment_setting('api_key', MOLLIE_PAYMENT_METHOD_NAME)"
        />
    </x-slot:fields>
</x-plugins-payment::settings-card>

