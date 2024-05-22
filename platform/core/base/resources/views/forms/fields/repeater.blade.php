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
    {!! Form::repeater($name, $options['value'] ?: Arr::get($options, 'selected'), $options['fields'] ?: []) !!}
</x-core::form.field>
