<x-plugins-payment::settings-card
    :name="Arr::get($formOptions, 'payment_name')"
    :id="Arr::get($formOptions, 'payment_id')"
    :logo="Arr::get($formOptions, 'payment_logo')"
    :url="Arr::get($formOptions, 'payment_url')"
    :description="Arr::get($formOptions, 'payment_description')"
    :default-description-value="Arr::get($formOptions, 'default_description_value')"
>
    <x-slot:instructions>
        {{ $form->getPaymentInstructions() }}
    </x-slot:instructions>

    @if ($showFields)
        <x-slot:fields>
            {{ $form->getOpenWrapperFormColumns() }}

            @foreach ($fields as $field)
                @continue(in_array($field->getName(), $exclude))

                {!! $field->render() !!}
            @endforeach

            {{ $form->getCloseWrapperFormColumns() }}
        </x-slot:fields>
    @endif
</x-plugins-payment::settings-card>
