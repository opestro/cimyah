<x-core::form.fieldset class="mb-3">
    <h4>{{ __('PayPal payout info') }}</h4>

    <x-core::datagrid>
        <x-core::datagrid.item>
            <x-slot:title>{{ __('Transaction ID') }}</x-slot:title>
            {{ $transactionId }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Status') }}</x-slot:title>
            {{ $status }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Amount') }}</x-slot:title>
            {{ $amount }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Fee') }}</x-slot:title>
            {{ $fee }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Created At') }}</x-slot:title>
            {{ $createdAt }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Completed At') }}</x-slot:title>
            {{ $completedAt }}
        </x-core::datagrid.item>

        <x-core::datagrid.item>
            <x-slot:title>{{ __('Funding Source') }}</x-slot:title>
            {{ $fundingSource }}
        </x-core::datagrid.item>
    </x-core::datagrid>
</x-core::form.fieldset>
