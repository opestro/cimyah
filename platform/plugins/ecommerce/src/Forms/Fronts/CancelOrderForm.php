<?php

namespace Botble\Ecommerce\Forms\Fronts;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\OrderCancellationReasonEnum;
use Botble\Ecommerce\Http\Requests\Fronts\CancelOrderRequest;

class CancelOrderForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->contentOnly()
            ->setFormOption('id', 'cancel-order-form')
            ->setValidatorClass(CancelOrderRequest::class)
            ->setUrl(route('customer.orders.cancel.post', $this->getModel()->id))
            ->add(
                'cancellation_reason',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Choose a Reason for Order Cancellation'))
                    ->choices([
                        '' => __('Choose a reason...'),
                        ...OrderCancellationReasonEnum::labels(),
                    ])
                    ->required()
                    ->toArray()
            )
            ->add(
                'cancellation_reason_description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->toArray()
            );
    }
}
