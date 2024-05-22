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
    @include('core/base::forms.partials.images', ['name' => $name, 'values' => Arr::get($options, 'values')])
</x-core::form.field>
