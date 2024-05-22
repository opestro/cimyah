<div class="btn-list">
    @if ($update)
        <x-core::button
            type="button"
            color="primary"
            class="btn-trigger-edit-product-version"
            :data-target="$update"
            :data-load-form="$loadForm"
            icon="ti ti-edit"
            :icon-only="true"
            :tooltip="trans('plugins/ecommerce::products.edit_variation_item')"
            size="sm"
        />
    @endif
    @if ($delete)
        <x-core::button
            type="button"
            color="danger"
            class="btn-trigger-delete-version"
            :data-target="$delete"
            :data-id="$item->id"
            :tooltip="trans('plugins/ecommerce::products.delete')"
            icon="ti ti-trash"
            :icon-only="true"
            size="sm"
        />
    @endif
</div>
