<ol>
    <li>
        <p>
            <a href="https://dashboard.stripe.com/register" target="_blank">
                {{ trans('plugins/payment::payment.service_registration', ['name' => 'Stripe']) }}
            </a>
        </p>
    </li>
    <li>
        <p>{{ trans('plugins/payment::payment.stripe_after_service_registration_msg', ['name' => 'Stripe']) }}</p>
    </li>
    <li>
        <p>{{ trans('plugins/payment::payment.stripe_enter_client_id_and_secret') }}</p>
    </li>
</ol>

<h4>{{ trans('plugins/stripe::stripe.webhook_setup_guide.title') }}</h4>

<p>{{ trans('plugins/stripe::stripe.webhook_setup_guide.description') }}</p>

<ol>
    <li>
        <p><strong>{{ trans('plugins/stripe::stripe.webhook_setup_guide.step_1_label') }}:</strong> {!! BaseHelper::clean(trans('plugins/stripe::stripe.webhook_setup_guide.step_1_description', ['link' => '<a href="https://dashboard.stripe.com/" target="_blank">Stripe Dashboard</a>'])) !!}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/stripe::stripe.webhook_setup_guide.step_2_label') }}:</strong> {!! BaseHelper::clean(trans('plugins/stripe::stripe.webhook_setup_guide.step_2_description', ['url' => '<code>' . route('payments.stripe.webhook') . '</code>'])) !!}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/stripe::stripe.webhook_setup_guide.step_3_label') }}:</strong> {{ trans('plugins/stripe::stripe.webhook_setup_guide.step_3_description') }}</p>
    </li>

    <li>
        <p><strong>{{ trans('plugins/stripe::stripe.webhook_setup_guide.step_4_label') }}:</strong> {{ trans('plugins/stripe::stripe.webhook_setup_guide.step_4_description') }}</p>
    </li>
</ol>
