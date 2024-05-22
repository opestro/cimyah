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
    {!! Form::text(
        $name,
        $options['value'],
        array_merge_recursive($options['attr'], ['class' => 'js-phone-number-mask form-control']),
    ) !!}
</x-core::form.field>
