<div class="box-table-shipping input-shipping-sync-wrapper box-table-shipping-item-{{ $rule ? $rule->id : 0 }} mb-2">
    <div class="accordion" id="accordion-rule-{{ $rule->id }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-rule-{{ $rule->id }}">
                <button
                    class="accordion-button collapsed px-3 py-2"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-rule-{{ $rule->id }}"
                    type="button"
                    aria-expanded="false"
                    aria-controls="collapse-rule-{{ $rule->id }}"
                >
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold label-rule-item-name">{{ $rule->name }}</span>
                            <div class="small">
                                @if ($rule->type->allowRuleItems())
                                    <span>{{ $rule->type->label() }}</span>
                                @else
                                    <span @class(['rule-to-value-missing', 'hidden' => $rule->to])>
                                        {{ trans('plugins/ecommerce::shipping.greater_than') }}
                                    </span>
                                    <span class="from-value-label">{{ $rule->type->toUnitText($rule->from) }}</span>
                                    <span @class(['rule-to-value-wrap', 'hidden' => !$rule->to])>
                                        <span class="m-1">-</span>
                                        <span class="to-value-label">{{ $rule->type->toUnitText($rule->to) }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="me-3">
                            <span class="rule-price-item">{{ format_price($rule->price ?? 0) }}</span>
                        </div>
                    </div>
                </button>
            </h2>
            <div
                class="accordion-collapse collapse"
                id="collapse-rule-{{ $rule->id }}"
                data-bs-parent="#accordion-rule-{{ $rule->id }}"
                aria-labelledby="heading-rule-{{ $rule->id }}"
            >
                <div class="accordion-body shipping-detail-information">
                    <x-core::form.fieldset>
                        @include('plugins/ecommerce::shipping.rules.form')
                    </x-core::form.fieldset>

                    @if ($rule && $rule->type->allowRuleItems() && Auth::user()->hasPermission('ecommerce.shipping-rule-items.index'))
                        @include('plugins/ecommerce::shipping.items.index', [
                            'total' => $rule->items_count,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
