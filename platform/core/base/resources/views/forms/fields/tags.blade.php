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
    @php
        $classAppend = ['tags'];

        if (Arr::has($options['attr'], 'data-url')) {
            $classAppend[] = 'list-tagify';
        }

        $options['attr']['class'] = (rtrim(Arr::get($options, 'attr.class'), ' ') ?: '') . ' ' . implode(' ', $classAppend);

        if (Arr::has($options, 'choices')) {
            $choices = $options['choices'];

            if ($choices instanceof \Illuminate\Support\Collection) {
                $choices = $choices->toArray();
            }

            if ($choices) {
                $options['attr']['data-list'] = json_encode($choices);
            }
        }

        if (Arr::has($options, 'selected')) {
            $options['value'] = $options['selected'];
        }
    @endphp

    {!! Form::text($name, $options['value'], $options['attr']) !!}
</x-core::form.field>
