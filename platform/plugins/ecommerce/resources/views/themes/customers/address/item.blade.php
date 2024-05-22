<div class="col mt-3">
    <div class="card mb-3 p-3">
        <p>
            {{ $address->name }}
            @if ($address->is_default)
                <x-core::badge color="info">{{ __('Default') }}</x-core::badge>
            @endif
        </p>
        <p><x-core::icon name="ti ti-book" class="me-1" /> {{ $address->full_address }}
        </p>
        <p><x-core::icon name="ti ti-phone" class="me-1" />{{ $address->phone }}</p>
        <div class="w-100 mt-3 d-flex gap-2">
            <a class="text-info d-inline-block" href="{{ route('customer.address.edit', $address->id) }}">{{ __('Edit') }}</a> |
            <x-core::form :url="route('customer.address.destroy', $address->id)" method="get" onsubmit="return confirm('{{ __('Are you sure you want to delete this address?') }}')">
                <button class="text-danger" type="submit" style="background: transparent !important; border: none; outline: none; padding: 0;">{{ __('Remove') }}</button>
            </x-core::form>
        </div>
    </div>
</div>
