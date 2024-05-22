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
    {!! Form::customSelect(
        $name,
        ($options['empty_value'] ? ['' => $options['empty_value']] : []) + $options['choices'],
        $options['selected'] !== null ? $options['selected'] : $options['default_value'],
        $options['attr'],
        Arr::get($options, 'optionAttrs', []),
        Arr::get($options, 'optgroupsAttributes', []),
    ) !!}
</x-core::form.field>
