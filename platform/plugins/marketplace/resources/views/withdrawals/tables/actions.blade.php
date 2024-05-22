<div class="table-actions">
    @if ($item->vendor_can_edit)
        <a
            class="btn btn-icon btn-sm btn-primary"
            data-bs-toggle="tooltip"
            data-bs-original-title="{{ trans('core/base::tables.edit') }}"
            href="{{ route('marketplace.vendor.withdrawals.edit', $item->id) }}"
        >
            <x-core::icon name="ti ti-edit" />
        </a>
    @else
        <a
            class="btn btn-icon btn-sm btn-success"
            data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Show') }}"
            href="{{ route('marketplace.vendor.withdrawals.show', $item->id) }}"
        >
            <x-core::icon name="ti ti-eye"></x-core::icon>
        </a>
    @endif
</div>
