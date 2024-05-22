<?php

namespace Botble\Payment\Forms;

use Botble\Base\Forms\FormAbstract;
use Illuminate\Support\HtmlString;

class PaymentMethodForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->contentOnly()
            ->template('plugins/payment::forms.payment-method');
    }

    protected function paymentId(string $id): static
    {
        $this->setFormOption('payment_id', $id);

        return $this;
    }

    protected function paymentName(string $name): static
    {
        $this->setFormOption('payment_name', $name);

        return $this;
    }

    protected function paymentDescription(string $description): static
    {
        $this->setFormOption('payment_description', $description);

        return $this;
    }

    protected function paymentLogo(string $logo): static
    {
        $this->setFormOption('payment_logo', $logo);

        return $this;
    }

    protected function paymentUrl(string $url): static
    {
        $this->setFormOption('payment_url', $url);

        return $this;
    }

    protected function paymentInstructions(string $paymentInstructions): static
    {
        $this->setFormOption('payment_instructions', $paymentInstructions);

        return $this;
    }

    protected function defaultDescriptionValue(string $value): static
    {
        $this->setFormOption('default_description_value', $value);

        return $this;
    }

    public function getPaymentInstructions(): HtmlString
    {
        return new HtmlString($this->getFormOption('payment_instructions'));
    }
}
