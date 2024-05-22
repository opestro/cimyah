<a
    href="javascript:void(0);"
    data-bb-toggle="clipboard"
    data-clipboard-action="{{ $copyableAction ?? 'copy' }}"
    data-clipboard-text="{{ $copyableState ?? '' }}"
    data-clipboard-message="{{ $copyableMessage ?? '' }}"
    data-bs-toggle="tooltip"
    title="{{ trans('core/table::table.copy') }}"
    class="text-muted text-center text-decoration-none {{ $copyablePositionClass ?? '' }}"
>
    <span class="sr-only">{{ trans('core/table::table.copy') }}</span>

    <x-core::icon name="ti ti-clipboard" data-clipboard-icon="true" />
    <x-core::icon name="ti ti-check" data-clipboard-success-icon="true" class="text-success d-none" />
</a>
