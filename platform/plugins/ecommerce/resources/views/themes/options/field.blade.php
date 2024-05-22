<div
    class="form-group mb-3 option-field product-option-{{ Str::slug($option->name) }} product-option-{{ $option->id }}"
    style="margin-bottom: 10px"
>
    <div class="product-option-item-wrapper">
        <div class="product-option-item-values">
            <div class="form-radio">
                @foreach ($option->values as $value)
                    @php
                        $price = 0;
                        if (!empty($value->affect_price) && doubleval($value->affect_price) > 0) {
                            $price = $value->affect_type == 0 ? $value->affect_price : (floatval($value->affect_price) * $product->price()->getPrice()) / 100;
                        }
                    @endphp
                    <div class="product-option-item-label">
                        <label class="form-label {{ $option->required ? 'required' : '' }}">
                            {{ $option->name }}
                            @if ($price > 0)
                                (<span class="extra-price">+ {{ format_price($price) }}</span>)
                            @endif
                        </label>
                    </div>
                    <input
                        name="options[{{ $option->id }}][option_type]"
                        type="hidden"
                        value="field"
                    />
                    <input
                        class="form-control"
                        id="option-{{ $option->id }}-value-{{ Str::slug($option->values[0]['option_value']) }}"
                        name="options[{{ $option->id }}][values]"
                        data-extra-price="0"
                        type="text"
                        {{ $option->required ? 'required' : '' }}
                    >
                @endforeach
            </div>
        </div>
    </div>
</div>
