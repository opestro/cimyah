@php
    if ($showLabel && empty($options['label'])) {
        $options['label'] = trans('core/base::forms.image');
    }
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
    {!! Form::mediaImage($name, $options['value'] ?? null, $options) !!}
</x-core::form.field>
