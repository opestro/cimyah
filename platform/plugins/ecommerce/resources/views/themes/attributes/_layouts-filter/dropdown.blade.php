@php
    $attributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($attributes->isNotEmpty())
    <div class="bb-product-filter">
        <h4 class="bb-product-filter-title">{{ $set->title }}</h4>

        <div class="bb-product-filter-content">
            <select class="form-select" name="attributes[{{ $set->slug }}][]">
                <option value="">{{ __('-- Select --') }}</option>
                @foreach ($attributes as $attribute)
                    <option value="{{ $attribute->id }}" @selected(in_array($attribute->id, $selected))>
                        {{ $attribute->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif

