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
    <input type="password" name="{{ $name }}" value="{{ $options['value'] }}" {!! Html::attributes($options['attr']) !!}>
</x-core::form.field>
