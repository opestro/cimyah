@php
    if (BaseHelper::hasIcon($value)) {
        $icon = BaseHelper::renderIcon($value);
    } else {
        $icon = $value;
    }
@endphp

<select name="{{ $name }}" data-bb-core-icon data-url="{{ route('core-icons') }}" data-placeholder="{{ trans('core/base::forms.select_placeholder') }}" {!! Html::attributes($attributes) !!}>
    @if ($value)
        <option value="{{ $value }}" selected>{{ $icon }}</option>
    @endif
</select>
