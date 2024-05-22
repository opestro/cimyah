@foreach ($productAttributeSets as $attributeSet)
    <x-core::form.checkbox
        :label="$attributeSet->title"
        class="attribute-set-item"
        name="attribute_sets[]"
        :value="$attributeSet->id"
        :checked="$attributeSet->is_selected"
        :inline="true"
    />
@endforeach

<x-core::alert type="warning" class="mt-3 mb-0">
    {{ trans('plugins/ecommerce::products.this_action_will_reload_page') }}
</x-core::alert>
