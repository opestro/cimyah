<div
    class="form-group mb-3 variant-radio product-option product-option-{{ Str::slug($option->name) }} product-option-{{ $option->id }}">
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
                value="dropdown"
            />
            <select
                class="form-select"
                name="options[{{ $option->id }}][values]"
                {{ $option->required ? 'required' : '' }}
            >
                @foreach ($option->values as $value)
                    @php
                        $price = 0;
                        if (!empty($value->affect_price) && doubleval($value->affect_price) > 0) {
                            $price = $value->affect_type == 0 ? $value->affect_price : (floatval($value->affect_price) * $product->price()->getPrice()) / 100;
                        }
                    @endphp
                    <option
                        data-extra-price="{{ $price }}"
                        value="{{ $value->option_value }}"
                    >{{ $value->option_value }} {{ $price > 0 ? '+' . format_price($price) : '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
