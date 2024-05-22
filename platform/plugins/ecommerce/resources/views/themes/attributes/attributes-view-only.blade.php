@if ($attributes->isNotEmpty())
    <div class="bb-product-attribute-list d-inline-block">
        @if (in_array($attributeSet->display_layout, ['text', 'dropdown']))
            <ul class="d-flex flex-wrap gap-2 list-unstyled mb-0">
                @foreach ($attributes as $attribute)
                    <li class="bg-body-tertiary border px-2">
                        {{ $attribute->title }}
                    </li>
                @endforeach
            </ul>
        @else
            <ul class="bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch">
                @foreach ($attributes as $attribute)
                    <li class="bb-product-attribute-swatch-item attribute-swatch-item">
                        <label>
                            @if ($attribute->image)
                                {{ RvMedia::image($attribute->image, $attribute->title, attributes: ['class' => 'rounded-pill']) }}
                            @else
                                <span style="background-color: {{ $attribute->color }} !important;"></span>
                            @endif
                            <div class="bb-product-attribute-swatch-item-tooltip">{{ $attribute->title }}</div>
                        </label>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@else
    &mdash;
@endif

