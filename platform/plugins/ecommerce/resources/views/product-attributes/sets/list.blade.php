@if (!$isNotDefaultLanguage)
    <x-core::custom-template id="product_attribute_template">
        <x-core::table.body.row data-id="__id__">
            <x-core::table.body.cell width="5%">
                <x-core::form.radio
                    name="related_attribute_is_default"
                    value="__position__"
                    __checked__
                    :single="true"
                    :checked="false"
                />
            </x-core::table.body.cell>

            <x-core::table.body.cell>
                <input type="text" name="swatch-title" class="form-control" value="__title__">
            </x-core::table.body.cell>

            <x-core::table.body.cell>
                <input type="text" name="swatch-value" data-bb-color-picker style="display: none" value="__color__">
            </x-core::table.body.cell>

            <x-core::table.body.cell width="5%">
                <x-core::form.image
                    :allow-thumb="true"
                    name="swatch-image"
                    value="__image__"
                    action="select-image"
                />
            </x-core::table.body.cell>

            <x-core::table.body.cell>
                <a href="javascript:(0)" class="remove-item text-decoration-none text-danger">
                    <x-core::icon name="ti ti-trash" />
                </a>
            </x-core::table.body.cell>
        </x-core::table.body.row>
    </x-core::custom-template>
    <textarea
        class="d-none"
        id="deleted_attributes"
        name="deleted_attributes"
    ></textarea>
@endif

<textarea
    class="d-none"
    id="attributes"
    name="attributes"
>{!! json_encode($attributes) !!}</textarea>

<x-core::table class="swatches-container text-center">
    <x-core::table.header class="header">
        @if (! $isNotDefaultLanguage)
            <x-core::table.header.cell width="5%">
                {{ trans('plugins/ecommerce::product-attribute-sets.is_default') }}
            </x-core::table.header.cell>
        @endif

        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::product-attribute-sets.title') }}
        </x-core::table.header.cell>

        @if(! $isNotDefaultLanguage)
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-attribute-sets.color') }}
            </x-core::table.header.cell>

            <x-core::table.header.cell width="5%">
                {{ trans('plugins/ecommerce::product-attribute-sets.image') }}
            </x-core::table.header.cell>

            <x-core::table.header.cell width="5%">
                {{ trans('plugins/ecommerce::product-attribute-sets.remove') }}
            </x-core::table.header.cell>
        @endif
    </x-core::table.header>

    <x-core::table.body class="swatches-list">
        @foreach ($attributes as $attribute)
            <x-core::table.body.row data-id="{{ $attribute->id }}">
                @if (! $isNotDefaultLanguage)
                    <x-core::table.body.cell>
                        <x-core::form.radio
                            name="related_attribute_is_default"
                            :value="$attribute->order"
                            :checked="$attribute->is_default"
                            :single="true"
                        />
                    </x-core::table.body.cell>
                @endif

                <x-core::table.body.cell>
                    <input class="form-control" name="swatch-title" type="text" value="{{ $attribute->title }}" />
                </x-core::table.body.cell>

                @if (!$isNotDefaultLanguage)
                    <x-core::table.body.cell>
                        <input data-bb-color-picker style="display: none" name="swatch-value" type="text" value="{{ $attribute->color }}" />
                    </x-core::table.body.cell>

                    <x-core::table.body.cell>
                        <x-core::form.image
                            :allow-thumb="true"
                            name="swatch-image"
                            :value="$attribute->image"
                            action="select-image"
                            :allow-add-from-url="false"
                        />
                    </x-core::table.body.cell>

                    <x-core::table.body.cell>
                        <a href="javascript:(0)" class="remove-item text-decoration-none text-danger">
                            <x-core::icon name="ti ti-trash" />
                        </a>
                    </x-core::table.body.cell>
                @endif
            </x-core::table.body.row>
        @endforeach
    </x-core::table.body>
</x-core::table>
