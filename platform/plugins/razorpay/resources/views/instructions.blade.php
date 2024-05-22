<ol>
    <li>
        <p>
            <a
                href="https://razorpay.com"
                target="_blank"
            >
                {{ __('Register an account on :name', ['name' => 'Razorpay']) }}
            </a>
        </p>
    </li>
    <li>
        <p>
            {{ __('After registration at :name, you will have Client ID, Client Secret', ['name' => 'Razorpay']) }}
        </p>
    </li>
    <li>
        <p>
            {{ __('Enter Client ID, Secret into the box in right hand') }}
        </p>
    </li>
    <li>
        <p>
            {!!
                BaseHelper::clean('Then you need to create a new webhook. To create a webhook, go to <strong>Account Settings</strong>-><strong>API keys</strong>-><strong>Webhooks</strong> and paste the below url to <strong>Webhook URL</strong> field. At <strong>Active Events</strong> field, check to <strong>Payment Events</strong> and <strong>Order Events</strong> checkbox.')
            !!}
        </p>

        <code>{{ route('payments.razorpay.webhook') }}</code>
    </li>
</ol>
