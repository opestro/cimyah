<div class="px-3 pb-4">
    @if ($result > 0)
        <span class="text-success">
            {{ __(':count increase', ['count' => number_format($result)]) }}
            <x-core::icon name="ti ti-trending-up" />
        </span>
    @elseif($result < 0)
        <span class="text-danger fw-semibold">
            {{ __(':count decrease', ['count' => number_format($result)]) }}
            <x-core::icon name="ti ti-trending-down" />
        </span>
    @else
        <span class="text-danger fw-semibold" style="visibility: hidden">&mdash;</span>
    @endif
</div>
