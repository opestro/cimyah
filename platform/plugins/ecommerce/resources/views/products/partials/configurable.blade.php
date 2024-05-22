<div id="product-variations-wrapper">
    {!! $productVariationTable->renderTable() !!}
</div>

<x-core::modal
    id="select-attribute-sets-modal"
    :title="trans('plugins/ecommerce::products.select_attribute')"
>
    @include('plugins/ecommerce::products.partials.attribute-sets', compact('productAttributeSets'))

    <x-slot:footer>
        <x-core::button
            type="button"
            data-bs-dismiss="modal"
        >
            {{ trans('core/base::base.close') }}
        </x-core::button>

        <x-core::button
            type="button"
            color="primary"
            id="store-related-attributes-button"
            class="ms-auto"
        >
            {{ trans('plugins/ecommerce::products.save_changes') }}
        </x-core::button>
    </x-slot:footer>
</x-core::modal>

@push('footer')
    <x-core::modal
        id="add-new-product-variation-modal"
        :title="trans('plugins/ecommerce::products.add_new_variation')"
        size="xl"
    >
        <x-core::loading />
        <x-slot:footer>
            <x-core::button
                type="button"
                data-bs-dismiss="modal"
            >
                {{ trans('core/base::base.close') }}
            </x-core::button>

            <x-core::button
                type="button"
                color="primary"
                id="store-product-variation-button"
                class="ms-auto"
            >
                {{ trans('plugins/ecommerce::products.save_changes') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal
        id="edit-product-variation-modal"
        :title="trans('plugins/ecommerce::products.edit_variation')"
        size="xl"
    >
        <x-core::loading />
        <x-slot:footer>
            <x-core::button
                type="button"
                data-bs-dismiss="modal"
            >
                {{ trans('core/base::base.close') }}
            </x-core::button>

            <x-core::button
                type="button"
                color="primary"
                id="update-product-variation-button"
                class="ms-auto"
            >
                {{ trans('plugins/ecommerce::products.save_changes') }}
            </x-core::button>
        </x-slot:footer>
    </x-core::modal>

    <x-core::modal.action
        id="generate-all-versions-modal"
        :title="trans('plugins/ecommerce::products.generate_all_variations')"
        :description="trans('plugins/ecommerce::products.generate_all_variations_confirmation')"
        :submit-button-attrs="['id' => 'generate-all-versions-button']"
        :submit-button-label="trans('plugins/ecommerce::products.continue')"
    />

    <x-core::modal.action
        id="confirm-delete-version-modal"
        type="danger"
        :title="trans('plugins/ecommerce::products.delete_variation')"
        :description="trans('plugins/ecommerce::products.delete_variation_confirmation')"
        :submit-button-attrs="['id' => 'delete-version-button']"
        :submit-button-label="trans('plugins/ecommerce::products.continue')"
    />

    <x-core::modal.action
        id="delete-variations-modal"
        type="danger"
        :title="trans('plugins/ecommerce::products.delete_variations')"
        :description="trans('plugins/ecommerce::products.delete_variations_confirmation')"
        :submit-button-attrs="['id' => 'delete-selected-variations-button']"
        :submit-button-label="trans('plugins/ecommerce::products.continue')"
    />
@endpush
