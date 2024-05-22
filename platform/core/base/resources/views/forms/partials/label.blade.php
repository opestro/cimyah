@php
    $for = $name;

    if (isset($attributes['for'])) {
        $for = $attributes['for'];
    }
@endphp
{!! Form::label($for, $label, $attributes, $escapeHtml) !!}
