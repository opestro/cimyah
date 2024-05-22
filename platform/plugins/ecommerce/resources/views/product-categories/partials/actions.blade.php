<a
    class="btn btn-icon btn-primary"
    data-bs-toggle="tooltip"
    data-bs-original-title="{{ trans('core/base::tables.edit') }}"
    href="{{ route('product-categories.edit', $item->id) }}"
>
    <x-core::icon name="ti ti-edit" />
</a>
@if (!$item->is_default)
    <a
        class="btn btn-icon btn-danger deleteDialog"
        data-bs-toggle="tooltip"
        data-section="{{ route('product-categories.destroy', $item->id) }}"
        data-bs-original-title="{{ trans('core/base::tables.delete_entry') }}"
        href="#"
        role="button"
    >
        <i class="fa fa-trash"></i>
    </a>
@endif
