<x-core::form
    :url="$rule ? route('shipping_methods.region.rule.update', $rule->id) : route('shipping_methods.region.rule.create')"
    :method="$rule ? 'PUT' : 'POST'"
>
    <x-core::form.text-input
        :required="true"
        :label="trans('plugins/ecommerce::shipping.shipping_rule_name')"
        name="name"
        class="input-sync-text-item"
        data-target=".label-rule-item-name"
        :value="$rule ? $rule->name : null"
    />

    <x-core::form-group>
        <x-core::form.label class="required">{{ trans('plugins/ecommerce::shipping.type') }}</x-core::form.label>
        {!! Form::customSelect(
            'type',
            ['' => trans('plugins/ecommerce::shipping.rule.select_type')] +
                Botble\Ecommerce\Enums\ShippingRuleTypeEnum::availableLabels($rule ? $rule->shipping : null),
            $rule ? $rule->type : '',
            ['class' => 'select-rule-type'],
            Botble\Ecommerce\Enums\ShippingRuleTypeEnum::toSelectAttributes(),
        ) !!}
    </x-core::form-group>

    <div class="rule-from-to-inputs" @style(['display: none' => $rule && !$rule->type->showFromToInputs()])>
        <x-core::form-group>
            <x-core::form.label class="rule-from-to-label">
                {{ $rule ? $rule->type->label() : Botble\Ecommerce\Enums\ShippingRuleTypeEnum::BASED_ON_PRICE()->label() }}
            </x-core::form.label>

            <div class="d-flex align-items-center gap-3">
                <div class="w-full">
                    <div class="input-group input-group-flat">
                        <span class="input-group-text unit-item-label">
                            {{ $rule ? $rule->type->toUnit() : Botble\Ecommerce\Enums\ShippingRuleTypeEnum::BASED_ON_PRICE()->toUnit() }}
                        </span>

                        <input
                            class="form-control input-mask-number input-sync-item"
                            name="from"
                            data-target=".from-value-label"
                            type="text"
                            value="{{ $rule ? $rule->from : 0 }}"
                        >
                    </div>
                </div>
                <span>-</span>
                <div class="w-full">
                    <div class="input-group input-group-flat">
                        <span class="input-group-text unit-item-label">
                            {{ $rule ? $rule->type->toUnit() : Botble\Ecommerce\Enums\ShippingRuleTypeEnum::BASED_ON_PRICE()->toUnit() }}
                        </span>

                        <input
                            class="form-control input-mask-number input-sync-item input-to-value-field"
                            name="to"
                            data-target=".to-value-label"
                            type="text"
                            value="{{ $rule && $rule->to != 0 ? $rule->to : null }}"
                        >
                    </div>
                </div>
            </div>
        </x-core::form-group>
    </div>

    <x-core::form.text-input
        :required="true"
        :label="trans('plugins/ecommerce::shipping.shipping_fee')"
        class="input-mask-number input-sync-item base-price-rule-item"
        name="price"
        :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
        :data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
        data-target=".rule-price-item"
        :value="$rule ? $rule->price : 0"
        :group-flat="true"
    >
        <x-slot:prepend>
            <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
        </x-slot:prepend>
    </x-core::form.text-input>

    @if ($rule)
        <div class="btn-list justify-content-between">
            <x-core::button
                type="button"
                color="danger"
                class="btn-confirm-delete-price-item-modal-trigger"
                :data-name="$rule->name"
                :data-id="$rule->id"
            >
                {{ trans('plugins/ecommerce::shipping.delete') }}
            </x-core::button>

            <div class="btn-list">
                <x-core::button
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-rule-{{ $rule->id }}"
                >
                    {{ trans('plugins/ecommerce::shipping.cancel') }}
                </x-core::button>
                <x-core::button
                    type="button"
                    color="primary"
                    class="btn-save-rule"
                >
                    {{ trans('plugins/ecommerce::shipping.save') }}
                </x-core::button>
            </div>
        </div>
    @endif
</x-core::form>
