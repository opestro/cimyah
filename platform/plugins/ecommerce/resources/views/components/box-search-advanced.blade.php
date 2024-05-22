@props([
    'label' => null,
    'type' => null,
    'searchTarget' => route('products.get-list-product-for-search'),
    'shown' => false,
    'heading' => trans('plugins/ecommerce::products.selected_products'),
    'template' => 'selected_product_list_template',
])

<div @class(['box-search-advance', $type]) data-template="{{ $template }}">
    {{ $slot }}

    <x-core::form.text-input
        :label="$label"
        name=""
        data-bb-toggle="product-search-advanced"
        :data-bb-target="$searchTarget"
        :placeholder="trans('plugins/ecommerce::products.search_products')"
    >
        <x-core::card class="position-absolute z-1 shadow w-100" style="display: none;" />
    </x-core::form.text-input>

    <div class="list-group list-group-flush list-group-hoverable list-selected-products" @style(['display: none' => ! $shown])>
        <x-core::form.label>{{ $heading }}</x-core::form.label>

        {{ $items }}
    </div>
</div>
