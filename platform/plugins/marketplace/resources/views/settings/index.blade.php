@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    {!! $form->renderForm() !!}
@stop

@push('footer')
    <x-core::custom-template id="commission-setting-item-template">
        <div class="row commission-setting-item" id="commission-setting-item-__index__">
            <div class="col-3">
                <x-core::form.text-input
                    :label="trans('plugins/marketplace::marketplace.settings.commission_fee')"
                    name="commission_by_category[__index__][commission_fee]"
                    type="number"
                    min="0"
                    max="100"
                />
            </div>
            <div class="col-9">
                <x-core::form.label
                    for="commission_fee_for_each_category"
                    :label="trans('plugins/marketplace::marketplace.settings.categories')"
                />
                <div class="row">
                    <div class="col-10">
                        <x-core::form.textarea
                            class="tagify-commission-setting"
                            name="commission_by_category[__index__][categories]" rows="3"
                            placeholder="{{ trans('plugins/marketplace::marketplace.settings.select_categories') }}"
                            tab-index="0"
                        />
                    </div>
                    <div class="col-2">
                        <x-core::button color="danger" data-bb-toggle="commission-remove" icon="ti ti-trash" :icon-only="true" />
                    </div>
                </div>
            </div>
        </div>
    </x-core::custom-template>

    <script>
        window.tagifyWhitelist = {!! Js::from($productCategories) !!}
    </script>
@endpush
