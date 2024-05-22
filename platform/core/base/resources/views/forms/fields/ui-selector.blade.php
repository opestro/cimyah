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
    {!! Form::uiSelector($name, Arr::get($options, 'selected'), $options['choices'], $options['attr']) !!}
</x-core::form.field>
