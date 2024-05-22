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
    {!! Form::multiChecklist(
        $name,
        $options['value'] ?: Arr::get($options, 'selected', []),
        $options['choices'],
        $options['attr'],
        Arr::get($options, 'empty_value'),
        Arr::get($options, 'inline', false),
        Arr::get($options, 'as_dropdown', false),
        Arr::get($options, 'attr.data-url'),
    ) !!}
</x-core::form.field>
