<x-core::custom-template id="attribute_item_wrap_template">
    <div class="product-attribute-set-item mb-3 mb-md-0" id="__id__">
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <x-core::form.select
                    :label="trans('plugins/ecommerce::products.form.attribute_name')"
                    class="product-select-attribute-item"
                />
            </div>
            <div class="col-sm-6 col-md-4">
                <x-core::form.select
                    :label="trans('plugins/ecommerce::products.form.value')"
                    class="product-select-attribute-item-value"
                />
            </div>

            <div class="col-md-4 col-sm-2 product-set-item-delete-action" style="display: none">
                <x-core::button
                    type="button"
                    color="danger"
                    icon="ti ti-trash"
                    :icon-only="true"
                    style="margin-top: 1.75rem"
                />
            </div>
        </div>
    </div>
</x-core::custom-template>
