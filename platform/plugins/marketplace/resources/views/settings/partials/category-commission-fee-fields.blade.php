<x-core::form.fieldset
    class="category-commission-fee-settings"
    @style(['display: none' => ! old('enable_commission_fee_for_each_category', MarketplaceHelper::isCommissionCategoryFeeBasedEnabled())])
>
    <div class="commission-setting-item-wrapper">
        @if (!empty($commissionEachCategory))
            @foreach ($commissionEachCategory as $fee => $commission)
                <div
                    class="row commission-setting-item"
                    id="commission-setting-item-{{ $loop->index }}"
                >
                    <div class="col-3">
                        <x-core::form.text-input
                            :label="trans('plugins/marketplace::marketplace.settings.commission_fee')"
                            name="commission_by_category[{{ $loop->index }}][commission_fee]"
                            type="number"
                            value="{{ $fee }}"
                            min="1"
                            max="100"
                        />
                    </div>

                    <div class="col-9">
                        <x-core::form.label for="commission_fee_for_each_category">
                            {{ trans('plugins/marketplace::marketplace.settings.categories') }}
                        </x-core::form.label>
                        <div class="row">
                            <div class="col-10">
                                <x-core::form.textarea
                                    class="tagify-commission-setting categories"
                                    name="commission_by_category[{{ $loop->index }}][categories]"
                                    rows="3"
                                    :value="$commission['categories'] ? json_encode($commission['categories']) : null"
                                    placeholder="{{ trans('plugins/marketplace::marketplace.settings.select_categories') }}"
                                >
                                    {{ Js::from($commission['categories'], true) }}
                                </x-core::form.textarea>
                            </div>
                            <div class="col-2">
                                @if ($loop->index > 0)
                                    <x-core::button
                                        data-bb-toggle="commission-remove"
                                        data-index="{{ $loop->index }}"
                                        icon="ti ti-trash"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div
                class="row commission-setting-item"
                id="commission-setting-item-0"
            >
                <div class="col-3">
                    <x-core::form.text-input
                        :label="trans('plugins/marketplace::marketplace.settings.commission_fee')"
                        name="commission_by_category[0][commission_fee]"
                        type="number"
                        min="1"
                        max="100"
                    />
                </div>
                <div class="col-9">
                    <x-core::form.label
                        class="form-label"
                        for="commission_fee_for_each_category"
                        :label="trans('plugins/marketplace::marketplace.settings.categories')"
                    />
                    <div class="row">
                        <div class="col-10">
                            <x-core::form.textarea
                                class="tagify-commission-setting"
                                name="commission_by_category[0][categories]"
                                rows="3"
                                :value="setting('marketplace_commission_by_category')"
                                placeholder="{{ trans('plugins/marketplace::marketplace.settings.select_categories') }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-core::button color="primary" data-bb-toggle="commission-category-add">
        {{ trans('plugins/marketplace::marketplace.settings.add_new') }}
    </x-core::button>
</x-core::form.fieldset>
