<?php

namespace Botble\Marketplace\Forms;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\FormAbstract;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\Fronts\VendorEditWithdrawalRequest;
use Botble\Marketplace\Http\Requests\Fronts\VendorWithdrawalRequest;
use Botble\Marketplace\Models\Withdrawal;

class VendorWithdrawalForm extends FormAbstract
{
    public function setup(): void
    {
        $fee = MarketplaceHelper::getSetting('fee_withdrawal', 0);

        $exists = $this->getModel() && $this->getModel()->id;

        $actionButtons = view('plugins/marketplace::withdrawals.forms.actions')->render();
        if ($exists) {
            $fee = null;
            if (! $this->getModel()->vendor_can_edit) {
                $actionButtons = ' ';
            }
        }

        $user = auth('customer')->user();
        $model = $user;
        $balance = $model->balance;
        $paymentChannel = $model->vendorInfo->payout_payment_method;

        if ($exists) {
            $model = $this->getModel();
            $paymentChannel = $model->payment_channel;
        }

        $this
            ->setupModel(new Withdrawal())
            ->setValidatorClass($exists ? VendorEditWithdrawalRequest::class : VendorWithdrawalRequest::class)
            ->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'))
            ->add(
                'amount',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/marketplace::withdrawal.forms.amount_with_balance', ['balance' => format_price($balance)]))
                    ->required()
                    ->placeholder(trans('plugins/marketplace::withdrawal.forms.amount_placeholder'))
                    ->attributes([
                        'data-counter' => 120,
                        'max' => $balance,
                    ])
                    ->disabled($exists)
                    ->helperText($fee ? trans(
                        'plugins/marketplace::withdrawal.forms.fee_helper',
                        ['fee' => format_price($fee)]
                    ) : '')
                    ->toArray()
            )
            ->when($exists, function (FormAbstract $form) {
                $form->add(
                    'fee',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(trans('plugins/marketplace::withdrawal.forms.fee'))
                        ->required()
                        ->disabled(true)
                        ->toArray()
                );
            })
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('core/base::forms.description'))
                    ->disabled($exists && ! $this->getModel()->vendor_can_edit)
                    ->placeholder(trans('core/base::forms.description_placeholder'))
                    ->attributes(['data-counter' => 200, 'rows' => 3])
                    ->toArray()
            )
            ->add('bankInfo', 'html', [
                'html' => view('plugins/marketplace::withdrawals.payout-info', [
                    'bankInfo' => $model->bank_info,
                    'taxInfo' => $user->tax_info,
                    'paymentChannel' => $paymentChannel,
                    'link' => $exists ? null : route('marketplace.vendor.settings', ['#tab_payout_info']),
                ])
                    ->render(),
            ]);

        if ($exists) {
            if ($model->images) {
                $this->addMetaBoxes([
                    'images' => [
                        'title' => __('Withdrawal images'),
                        'content' => view('plugins/marketplace::withdrawals.forms.images', compact('model'))->render(),
                        'priority' => 4,
                    ],
                ]);
            }

            if ($this->getModel()->vendor_can_edit) {
                $this->add('cancel', 'onOff', [
                    'label' => __('Do you want to cancel?'),
                    'help_block' => [
                        'text' => __('After cancel amount and fee will be refunded back in your balance'),
                    ],
                ]);
            } else {
                $this->add('cancel', 'html', [
                    'label' => trans('core/base::tables.status'),
                    'html' => $model->status->toHtml(),
                ]);
            }
        }

        $this
            ->setBreakFieldPoint('cancel')
            ->setActionButtons($actionButtons);
    }
}
