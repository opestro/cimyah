@php
    $value = count($values) ? $values[0] ?? [] : [];
    $isDefaultLanguage = !defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME') || !request()->input('ref_lang') || request()->input('ref_lang') == Language::getDefaultLocaleCode();
@endphp

<div
    class="col-md-12 option-setting-tab"
    id="option-setting-multiple"
    @if ($isDefaultLanguage) style="display: none" @endif
>
    <x-core::table :striped="false" :hover="false" class="table-bordered setting-option table-vcenter">
        <x-core::table.header>
            @if ($isDefaultLanguage)
                <x-core::table.header.cell scope="col">#</x-core::table.header.cell>
            @endif
            <th scope="col">{{ trans('plugins/ecommerce::product-option.label') }}</th>
            @if ($isDefaultLanguage)
                <x-core::table.header.cell scope="col">
                    {{ trans('plugins/ecommerce::product-option.price') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell scope="col" colspan="2">
                    {{ trans('plugins/ecommerce::product-option.price_type') }}
                </x-core::table.header.cell>
            @endif
        </x-core::table.header>
        <x-core::table.body class="option-sortable">
            @if ($values->count())
                @foreach ($values as $key => $value)
                    <x-core::table.body.row
                        class="option-row ui-state-default"
                        data-index="{{ $key }}"
                    >
                        <input
                            name="options[{{ $key }}][id]"
                            type="hidden"
                            value="{{ $value->id }}"
                        >
                        <input
                            name="options[{{ $key }}][order]"
                            type="hidden"
                            value="{{ $value->order !== 9999 ? $value->order : $key }}"
                        >
                        @if ($isDefaultLanguage)
                            <x-core::table.body.cell class="text-center">
                                <x-core::icon name="ti ti-arrows-sort" />
                            </x-core::table.body.cell>
                        @endif
                        <x-core::table.body.cell>
                            <input
                                class="form-control option-label"
                                name="options[{{ $key }}][option_value]"
                                type="text"
                                value="{{ $value->option_value }}"
                                placeholder="{{ trans('plugins/ecommerce::product-option.label_placeholder') }}"
                            />
                        </x-core::table.body.cell>
                        @if ($isDefaultLanguage)
                            <x-core::table.body.cell>
                                <input
                                    class="form-control affect_price"
                                    name="options[{{ $key }}][affect_price]"
                                    type="number"
                                    value="{{ $value->affect_price }}"
                                    placeholder="{{ trans('plugins/ecommerce::product-option.affect_price_label') }}"
                                />
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <select
                                    class="form-select affect_type"
                                    name="options[{{ $key }}][affect_type]"
                                >
                                    <option
                                        value="0"
                                        {{ $value->affect_type == 0 ? 'selected' : '' }}
                                    >{{ trans('plugins/ecommerce::product-option.fixed') }}</option>
                                    <option
                                        value="1"
                                        {{ $value->affect_type == 1 ? 'selected' : '' }}
                                    >{{ trans('plugins/ecommerce::product-option.percent') }}</option>
                                </select>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell style="width: 50px">
                                <x-core::button
                                    class="remove-row"
                                    data-index="0"
                                    icon="ti ti-trash"
                                    :icon-only="true"
                                />
                            </x-core::table.body.cell>
                        @endif
                    </x-core::table.body.row>
                @endforeach
            @else
                <x-core::table.body.row
                    class="option-row"
                    data-index="0"
                >
                    @if ($isDefaultLanguage)
                        <x-core::table.body.cell class="text-center">
                            <x-core::icon name="ti ti-arrows-sort" />
                        </x-core::table.body.cell>
                    @endif
                    <x-core::table.body.cell>
                        <input
                            class="form-control option-label"
                            name="options[0][option_value]"
                            type="text"
                            value=""
                            placeholder="{{ trans('plugins/ecommerce::product-option.label_placeholder') }}"
                        />
                    </x-core::table.body.cell>
                    @if ($isDefaultLanguage)
                        <x-core::table.body.cell>
                            <input
                                class="form-control affect_price"
                                name="options[0][affect_price]"
                                type="number"
                                value=""
                                placeholder="{{ trans('plugins/ecommerce::product-option.affect_price_label') }}"
                            />
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                            <select
                                class="form-select affect_type"
                                name="options[0][affect_type]"
                            >
                                <option value="0">{{ trans('plugins/ecommerce::product-option.fixed') }}</option>
                                <option value="1">{{ trans('plugins/ecommerce::product-option.percent') }}
                                </option>
                            </select>
                        </x-core::table.body.cell>
                        <x-core::table.body.cell style="width: 50px">
                            <x-core::button
                                class="remove-row"
                                data-index="0"
                                icon="ti ti-trash"
                                :icon-only="true"
                            />
                        </x-core::table.body.cell>
                    @endif
                </x-core::table.body.row>
            @endif
        </x-core::table.body>
    </x-core::table>
    @if ($isDefaultLanguage)
        <x-core::button
            type="button"
            class="add-new-row mt-3"
            id="add-new-row"
        >
            {{ trans('plugins/ecommerce::product-option.add_new_row') }}
        </x-core::button>
    @endif
</div>

@if ($isDefaultLanguage)
    <div class="empty">{{ trans('plugins/ecommerce::product-option.please_choose_option_type') }}</div>
@endif
