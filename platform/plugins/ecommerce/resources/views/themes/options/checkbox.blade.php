<div
    class="form-group mb-3 variant-radio product-option product-option-{{ Str::slug($option->name) }} product-option-{{ $option->id }}"
    style="margin-bottom:10px"
>
    <div class="product-option-item-wrapper">
        <div class="product-option-item-label">
            <label class="{{ $option->required ? 'required' : '' }}">
                {{ $option->name }}
            </label>
        </div>
        <div class="product-option-item-values">
            <input
                name="options[{{ $option->id }}][option_type]"
                type="hidden"
                value="checkbox"
            />
            @foreach ($option->values as $value)
                @php
                    $price = 0;
                    if (!empty($value->affect_price) && doubleval($value->affect_price) > 0) {
                        $price = $value->affect_type == 0 ? $value->affect_price : (floatval($value->affect_price) * $product->price()->getPrice()) / 100;
                    }
                @endphp
                <div class="{{ $wrapperClass ?? 'form-checkbox' }}">
                    <input
                        id="option-{{ $option->id }}-value-{{ Str::slug($value->option_value) }}"
                        name="options[{{ $option->id }}][values][]"
                        data-extra-price="{{ $price }}"
                        type="checkbox"
                        value="{{ $value->option_value }}"
                        @if (isset($inputClass)) class="{{ $inputClass }}" @endif
                        @if ($option->required && $loop->first) checked @endif
                    >
                    <label for="option-{{ $option->id }}-value-{{ Str::slug($value->option_value) }}" @if (isset($labelClass)) class="{{ $labelClass }}" @endif>
                        &nbsp;{{ $value->option_value }}
                        @if ($price > 0)
                            <strong class="extra-price">+ {{ format_price($price) }}</strong>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
