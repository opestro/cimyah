@php
    $options = [
        ...$options,
        'label_attr' => [
            'class' => 'required',
        ],
        'label' => app('math-captcha')->label(),
    ];
@endphp

<x-core::form.field
    :showLabel="$showLabel"
    :showField="$showField"
    :options="$options"
    :name="$name"
    :prepend="$prepend ?? null"
    :append="$append ?? null"
    :showError="$showError"
    :nameKey="$nameKey"
>
    {!! app('math-captcha')->input(['class' => 'form-control', 'id' => 'math-group']) !!}
</x-core::form.field>
