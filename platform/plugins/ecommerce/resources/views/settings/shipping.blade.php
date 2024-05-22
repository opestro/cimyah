@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core-setting::section
        :title="trans('plugins/ecommerce::setting.shipping.shipping_rule')"
        :description="trans('plugins/ecommerce::setting.shipping.shipping_rule_description')"
        class="wrapper-content"
    >
        <x-slot:extra-description>
            <x-core::button
                type="button"
                class="btn-select-country"
            >
                {{ trans('plugins/ecommerce::shipping.select_country') }}
            </x-core::button>
        </x-slot:extra-description>

        @if(! EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
            <div class="px-3">
                <x-core::alert
                    type="info">
                    {{ trans('plugins/ecommerce::shipping.shipping_based_on_location_instruction', ['link_text' => trans('plugins/ecommerce::setting.checkout.form.load_countries_states_cities_from_location_plugin') ]) }}
                </x-core::alert>
            </div>
        @endif

        @if(! EcommerceHelper::isZipCodeEnabled())
            <div class="px-3">
                <x-core::alert
                    type="info">
                    {{ trans('plugins/ecommerce::shipping.shipping_based_on_zip_code_instruction', ['link_text' => trans('plugins/ecommerce::setting.checkout.form.zip_code_enabled') ]) }}
                </x-core::alert>
            </div>
        @endif

        @if (! empty($shipping))
            @foreach ($shipping as $shippingItem)
                <div class="p-3 wrap-table-shipping-{{ $shippingItem->id }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <x-core::form.label>
                            {{ trans('plugins/ecommerce::shipping.country') }}
                            <strong>{{ Arr::get(EcommerceHelper::getAvailableCountries(), $shippingItem->title, $shippingItem->title) }}</strong>
                        </x-core::form.label>

                        <div class="btn-list">
                            <a
                                href="javascript:void(0);"
                                data-shipping-id="{{ $shippingItem->id }}"
                                data-country="{{ $shippingItem->country}}"
                                class="btn-add-shipping-rule-trigger"
                            >
                                {{ trans('plugins/ecommerce::shipping.add_shipping_rule') }}
                            </a>
                            <a
                                href="javascript:void(0);"
                                data-id="{{ $shippingItem->id }}"
                                data-name="{{ Arr::get(EcommerceHelper::getAvailableCountries(), $shippingItem->title, $shippingItem->title) }}"
                                class="btn-confirm-delete-region-item-modal-trigger text-danger"
                            >
                                {{ trans('plugins/ecommerce::shipping.delete') }}
                            </a>
                        </div>
                    </div>
                    <div>
                        @foreach ($shippingItem->rules as $rule)
                            @include('plugins/ecommerce::shipping.rules.item', compact('rule'))
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        @php
            $extraShippingOptions = apply_filters(SHIPPING_METHODS_SETTINGS_PAGE, null);
        @endphp

        @if ($extraShippingOptions)
            <div class="p-3">
                {!! apply_filters(SHIPPING_METHODS_SETTINGS_PAGE, null) !!}
            </div>
        @else
            <x-core::empty-state
                :title="trans('plugins/ecommerce::shipping.empty_shipping_options.title')"
                :subtitle="trans('plugins/ecommerce::shipping.empty_shipping_options.subtitle')"
            />
        @endif

    </x-core-setting::section>

    {!! $form->renderForm() !!}
@endsection

@push('footer')
    <x-core::modal.action
        id="confirm-delete-price-item-modal"
        type="danger"
        :title="trans('plugins/ecommerce::shipping.delete_shipping_rate')"
        :description="trans('plugins/ecommerce::shipping.delete_shipping_rate_confirmation')"
        :submit-button-attrs="['id' => 'confirm-delete-price-item-button']"
        :submit-button-label="trans('plugins/ecommerce::shipping.confirm')"
    />

    <x-core::modal.action
        id="confirm-delete-region-item-modal"
        type="danger"
        :title="trans('plugins/ecommerce::shipping.delete_shipping_area')"
        :description="trans('plugins/ecommerce::shipping.delete_shipping_area_confirmation')"
        :submit-button-attrs="['id' => 'confirm-delete-region-item-button']"
        :submit-button-label="trans('plugins/ecommerce::shipping.confirm')"
    />

    <x-core::modal
        id="add-shipping-rule-item-modal"
        :title="trans('plugins/ecommerce::shipping.add_shipping_fee_for_area')"
        button-id="add-shipping-rule-item-button"
        :button-label="trans('plugins/ecommerce::shipping.save')"
    >
        @include('plugins/ecommerce::shipping.rules.form', ['rule' => null])
    </x-core::modal>

    <div data-delete-region-item-url="{{ route('shipping_methods.region.destroy') }}"></div>
    <div data-delete-rule-item-url="{{ route('shipping_methods.region.rule.destroy') }}"></div>

    <x-core::modal
        id="select-country-modal"
        :title="trans('plugins/ecommerce::shipping.add_shipping_region')"
        button-id="add-shipping-region-button"
        :button-label="trans('plugins/ecommerce::shipping.save')"
    >
        {!! Botble\Ecommerce\Forms\AddShippingRegionForm::create()->renderForm() !!}
    </x-core::modal>

    <x-core::modal
        id="form-shipping-rule-item-detail-modal"
        :title="trans('plugins/ecommerce::shipping.add_shipping_region')"
        button-id="save-shipping-rule-item-detail-button"
        :button-label="trans('plugins/ecommerce::shipping.save')"
    >
        Loading...
    </x-core::modal>

    <x-core::modal.action
        id="confirm-delete-shipping-rule-item-modal"
        :title="trans('plugins/ecommerce::shipping.rule.item.delete')"
        :description="trans('plugins/ecommerce::shipping.rule.item.confirmation')"
        :submit-button-attrs="['id' => 'confirm-delete-shipping-rule-item-button']"
        :submit-button-label="trans('plugins/ecommerce::shipping.confirm')"
    />
@endpush
