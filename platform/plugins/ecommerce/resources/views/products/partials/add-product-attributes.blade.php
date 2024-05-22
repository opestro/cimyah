@if ($productAttributeSets->isNotEmpty())
    <div class="add-new-product-attribute-wrap">
        <input
            id="is_added_attributes"
            name="is_added_attributes"
            type="hidden"
            value="0"
        >
        <p class="text-muted">{{ trans('plugins/ecommerce::products.form.add_new_attributes_description') }}</p>
        <div class="list-product-attribute-values-wrap" style="display: none">
            <div class="product-select-attribute-item-template"></div>
        </div>

        <x-core::form.fieldset class="list-product-attribute-wrap" style="display: none">
            <div class="list-product-attribute-items-wrap"></div>

            <div class="btn-list">
                <x-core::button
                    class="btn-trigger-add-attribute-item"
                    @style(['display: none;' => $productAttributeSets->count() < 2])
                >
                    {{ trans('plugins/ecommerce::products.form.add_more_attribute') }}
                </x-core::button>
                @if (!empty($addAttributeToProductUrl))
                    <x-core::button
                        type="button"
                        color="info"
                        class="btn-trigger-add-attribute-to-simple-product"
                        :data-target="$addAttributeToProductUrl"
                        :tooltip="trans('plugins/ecommerce::products.this_action_will_reload_page')"
                    >
                        {{ trans('plugins/ecommerce::products.form.continue') }}
                    </x-core::button>
                @endif
            </div>
            @if ($product && is_object($product) && $product->id)
                <x-core::alert type="warning" class="mt-3 mb-0">
                    {{ trans('plugins/ecommerce::products.this_action_will_reload_page') }}
                </x-core::alert>
            @endif
        </x-core::form.fieldset>
    </div>
@elseif (is_in_admin(true) && Auth::check() && Auth::user()->hasPermission('product-attribute-sets.create'))
    <p class="text-muted mb-0">
        {!! trans('plugins/ecommerce::products.form.create_product_variations', [
            'link' => Html::link(
                route('product-attribute-sets.create'),
                trans('plugins/ecommerce::products.form.add_new_attributes'),
                ['target' => '_blank']
            ),
        ]) !!}
    </p>
@endif

@once
    @if (request()->ajax())
        @include('plugins/ecommerce::products.partials.select-product-attributes-template')
    @else
        @push('footer')
            @include('plugins/ecommerce::products.partials.select-product-attributes-template')
        @endpush
    @endif
@endonce
