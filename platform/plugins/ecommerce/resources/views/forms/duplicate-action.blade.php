<x-core::button
    type="button"
    icon="ti ti-copy"
    class="btn-trigger-duplicate-product"
    data-url="{{ route('products.duplicate', $product->getKey()) }}"
>
    {{ trans('plugins/ecommerce::ecommerce.forms.duplicate') }}
</x-core::button>

@push('footer')
    <x-core::modal.action
        id="duplicate-product-modal"
        type="info"
        :title="trans('plugins/ecommerce::ecommerce.duplicate_modal')"
        :submit-button-attrs="['id' => 'confirm-duplicate-product-button']"
        :submit-button-label="trans('plugins/ecommerce::ecommerce.forms.duplicate')"
    >
        {{ trans('plugins/ecommerce::ecommerce.duplicate_modal_description') }}
    </x-core::modal.action>
@endpush
