@php
    Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/product-option.js');

    $product = $product->loadMissing([
        'options' => function ($query) {
            return $query->with(['values']);
        },
    ]);
    $oldOption = old('options', []) ?? [];
    $currentProductOption = $product->options;
    foreach ($currentProductOption as $key => $option) {
        $currentProductOption[$key]['name'] = $option->name;
        foreach ($option['values'] as $valueKey => $value) {
            $currentProductOption[$key]['values'][$valueKey]['option_value'] = $value->option_value;
        }
    }

    if (!empty($oldOption)) {
        $currentProductOption = $oldOption;
    }

    $isDefaultLanguage = !defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME') || !request()->input('ref_lang') || request()->input('ref_lang') == Language::getDefaultLocaleCode();
@endphp

@push('header')
    <script>
        window.productOptions = {
            productOptionLang: {!! Js::from(trans('plugins/ecommerce::product-option')) !!},
            coreBaseLang: {!! Js::from(trans('core/base::forms')) !!},
            currentProductOption: {!! Js::from($currentProductOption) !!},
            options: {!! Js::from($options) !!},
            routes: {!! Js::from($routes) !!},
            isDefaultLanguage: {{ (int) $isDefaultLanguage }}
        }
    </script>
@endpush

<div class="product-option-form-wrap">
    <div class="product-option-form-group">
        <div class="product-option-form-body">
            <input
                name="has_product_options"
                type="hidden"
                value="1"
            >
            <div
                class="accordion"
                id="accordion-product-option"
            ></div>
        </div>
        <div class="row">
            @if ($isDefaultLanguage)
                <div class="col">
                    <x-core::button
                        type="button"
                        class="add-new-option"
                        id="add-new-option"
                    >
                        {{ trans('plugins/ecommerce::product-option.add_new_option') }}
                    </x-core::button>
                </div>
                @if (! empty($globalOptions))
                    <div class="col ms-auto">
                        <div class="d-flex gap-2 align-items-start justify-content-end">
                            <x-core::form.select
                                id="global-option"
                                :options="[0 => trans('plugins/ecommerce::product-option.select_global_option')] + $globalOptions"
                            />
                            <x-core::button
                                type="button"
                                class="add-from-global-option"
                            >
                                {{ trans('plugins/ecommerce::product-option.add_global_option') }}
                            </x-core::button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('footer')
    <x-core::custom-template id="template-option-values-of-field">
        <table class="table table-bordered setting-option mt-3">
            <thead>
            <tr>
                @if ($isDefaultLanguage)
                    <th scope="col">__priceLabel__</th>
                    <th scope="col" colspan="2">__priceTypeLabel__</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <tr>
                <input type="hidden" name="options[__index__][values][0][id]" value="__id__" />
                @if ($isDefaultLanguage)
                    <td>
                        <input type="number" name="options[__index__][values][0][affect_price]" class="form-control option-label" value="__affectPrice__" placeholder="__affectPriceLabel__"/>
                    </td>
                    <td>
                        <select class="form-select" name="options[__index__][values][0][affect_type]">
                            <option value="0" __selectedFixed__> __fixedLang__</option>
                            <option value="1" __selectedPercent__> __percentLang__</option>
                        </select>
                    </td>
                @endif
            </tr>
            </tbody>
        </table>
    </x-core::custom-template>
    <x-core::custom-template id="template-option-type-array">
        <table class="table table-bordered setting-option mt-3">
            <thead>
            <tr class="option-row">
                @if ($isDefaultLanguage)
                    <th scope="col">#</th>
                @endif
                <th scope="col">__label__</th>
                @if ($isDefaultLanguage)
                    <th scope="col">__priceLabel__</th>
                    <th scope="col" colspan="2">__priceTypeLabel__</th>
                @endif
            </tr>
            </thead>
            <tbody>
            __optionValue__
            </tbody>
        </table>
    </x-core::custom-template>

    <x-core::custom-template id="template-option-type-value">
        <tr data-index="__key__">
            @if ($isDefaultLanguage)
                <td>
                    <i class="fa fa-sort"></i>
                    <input type="hidden" class="option-value-order" value="__order__" name="options[__index__][values][__key__][order]">
                </td>
            @endif
            <td>
                <input type="hidden" class="option-value-order" value="__id__" name="options[__index__][values][__key__][id]">
                <input type="text" name="options[__index__][values][__key__][option_value]" class="form-control option-label" value="__option_value_input__" placeholder="__labelPlaceholder__" />
            </td>
            @if ($isDefaultLanguage)
                <td>
                    <input type="number" name="options[__index__][values][__key__][affect_price]" class="form-control affect_price" value="__affectPrice__" placeholder="__affectPriceLabel__" />
                </td>
                <td>
                    <select class="form-select affect_type" name="options[__index__][values][__key__][affect_type]">
                        <option value="0" __selectedFixed__> __fixedLang__ </option>
                        <option value="1" __selectedPercent__> __percentLang__ </option>
                    </select>
                </td>
                <td style="width: 50px;">
                    <button class="btn btn-default remove-row"><i class="fa fa-trash"></i></button>
                </td>
            @endif
        </tr>
    </x-core::custom-template>

    <x-core::custom-template id="template-option">
        <div class="accordion-item mb-3" data-index="__index__" data-product-option-index="__index__">
            <input type="hidden" name="options[__index__][id]" value="__id__" />
            <input type="hidden" class="option-order" name="options[__index__][order]" value="__order__" />
            <h2 class="accordion-header" id="product-option-__index__">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-product-option-__index__" aria-expanded="true" aria-controls="product-option-__index__">
                    __optionName__
                </button>
            </h2>
            <div id="collapse-product-option-__index__" class="accordion-collapse collapse-product-option show" aria-labelledby="product-option-__id__" data-bs-parent="#accordion-product-option">
                <div class="accordion-body">
                    <div class="row align-items-end">
                        <div class="col">
                            <x-core::form.label>__nameLabel__</x-core::form.label>
                            <input type="text" name="options[__index__][name]" class="form-control option-name" value="__option_name__" placeholder="__namePlaceHolder__">
                        </div>
                        @if ($isDefaultLanguage)
                            <div class="col">
                                <x-core::form.label>__optionTypeLabel__</x-core::form.label>
                                <select name="options[__index__][option_type]" id="" class="form-select option-type">
                                    __optionTypeOption__
                                </select>
                            </div>
                            <div class="col" style="margin-top: 38px;">
                                <div class="mb-3">
                                    <x-core::form.checkbox
                                        label="__requiredLabel__"
                                        id="required-__index__"
                                        name="options[__index__][required]"
                                        class="option-required"
                                        value="1"
                                        __checked__=""
                                    />
                                </div>
                            </div>
                            <div class="col text-end">
                                <x-core::button
                                    type="button"
                                    color="danger"
                                    data-index="__index__"
                                    class="remove-option"
                                    icon="ti ti-trash"
                                    :icon-only="true"
                                />
                            </div>
                        @endif
                    </div>
                    <div class="option-value-wrapper option-value-sortable">
                        __optionValueSortable__
                    </div>
                </div>
            </div>
        </div>
    </x-core::custom-template>
@endpush
