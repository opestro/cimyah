<div class="row">
    @foreach ($productAttributeSets as $attributeSet)
        <div class="col-md-4 col-sm-6">
            <x-core::form-group>
                <x-core::form.label for="attribute-{{ $attributeSet->slug }}" class="required">
                    {{ $attributeSet->title }}
                </x-core::form.label>

                @php
                    if ($selected = $productVariationsInfo ? $productVariationsInfo->firstWhere('attribute_set_id', $attributeSet->id) : null) {
                        $selected = [$selected->id => $selected->title];
                    } else {
                        $selected = ['' => '-- ' . trans('plugins/ecommerce::products.select') . ' --'];
                    }
                @endphp

                <x-core::form.select
                    name="attribute_sets[{{ $attributeSet->id }}]"
                    :value="Arr::first(array_keys($selected))"
                    :options="$selected"
                    :data-id="$attributeSet->id"
                    class="select-attributes"
                />
            </x-core::form-group>
        </div>
    @endforeach
</div>
