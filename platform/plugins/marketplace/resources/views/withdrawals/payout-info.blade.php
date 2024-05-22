<x-core::form.fieldset class="mb-3">
    <h4>{{ $title ?? __('You will receive money through the information below') }}</h4>

    <x-core::datagrid>
        @foreach (Botble\Marketplace\Enums\PayoutPaymentMethodsEnum::getFields($paymentChannel) as $key => $field)
            @if (Arr::get($bankInfo, $key))
                <x-core::datagrid.item>
                    <x-slot:title>{{ Arr::get($field, 'title') }}</x-slot:title>
                    {{ Arr::get($bankInfo, $key) }}
                </x-core::datagrid.item>
            @endif
        @endforeach
    </x-core::datagrid>
</x-core::form.fieldset>

@isset($link)
    <p class="mb-3">{!! BaseHelper::clean(__('You can change it <a href=":link">here</a>', ['link' => $link])) !!}.</p>
@endisset

@if ($taxInfo && (Arr::get($taxInfo, 'business_name') || Arr::get($taxInfo, 'tax_id') || Arr::get($taxInfo, 'address')))
    <x-core::form.fieldset class="mb-3">
        <h4>{{ __('Tax info') }}</h4>

        <x-core::datagrid>
            @if (Arr::get($taxInfo, 'business_name'))
                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Business Name') }}</x-slot:title>
                    {{ Arr::get($taxInfo, 'business_name') }}
                </x-core::datagrid.item>
            @endif

            @if ($taxId = Arr::get($taxInfo, 'tax_id'))
                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Tax ID') }}</x-slot:title>
                    {{ $taxId }}
                </x-core::datagrid.item>
            @endif

            @if ($address = Arr::get($taxInfo, 'address'))
                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Address') }}</x-slot:title>
                    {{ $address }}
                </x-core::datagrid.item>
            @endif
        </x-core::datagrid>
    </x-core::form.fieldset>
@endif
