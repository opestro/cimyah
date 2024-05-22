@php
    $fields = Arr::get($options, 'fields', []);
    $attributes = Arr::get($options, 'shortcode_attributes', []);
    $min = Arr::get($options, 'min', 1);
    $max = Arr::get($options, 'max', 20);
@endphp

{!! shortcode()->fields()->tabs($fields, $attributes, $max, $min) !!}
