<ol>
    <li>
        <p>
            <a
                href="https://www.paypal.com/merchantsignup/applicationChecklist?signupType=CREATE_NEW_ACCOUNT&amp;productIntentId=email_payments"
                target="_blank"
            >
                {{ trans('plugins/payment::payment.service_registration', ['name' => 'PayPal']) }}
            </a>
        </p>
    </li>
    <li>
        <p>
            {{ trans('plugins/payment::payment.after_service_registration_msg', ['name' => 'PayPal']) }}
        </p>
    </li>
    <li>
        <p>
            {{ trans('plugins/payment::payment.enter_client_id_and_secret') }}
        </p>
    </li>
</ol>
