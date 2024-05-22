@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    {!! $form->renderForm() !!}
@stop

@push('footer')
    <x-core::custom-template id="currency_template">
        <div id="loading-update-currencies" style="display: none;">
            <div class="currency-loading-backdrop"></div>
            <div class="currency-loading-loader"></div>
        </div>
        <li data-id="__id__" class="clearfix">
            <div class="swatch-item" data-type="title">
                <input type="text" class="form-control" value="__title__">
            </div>
            <div class="swatch-item" data-type="symbol">
                <input type="text" class="form-control" value="__symbol__">
            </div>
            <div class="swatch-item swatch-decimals" data-type="decimals">
                <input type="number" class="form-control" value="__decimals__">
            </div>
            <div class="swatch-item swatch-exchange-rate" data-type="exchange_rate">
                <input type="number" @disabled(get_ecommerce_setting('use_exchange_rate_from_api')) class="form-control input-exchange-rate" value="__exchangeRate__" step="0.00000001">
            </div>
            <div class="swatch-item swatch-is-prefix-symbol" data-type="is_prefix_symbol">
                <select class="form-select">
                    <option value="1" __isPrefixSymbolChecked__>{{ trans('plugins/ecommerce::currency.before_number') }}</option>
                    <option value="0" __notIsPrefixSymbolChecked__>{{ trans('plugins/ecommerce::currency.after_number') }}</option>
                </select>
            </div>
            <div class="swatch-is-default" data-type="is_default">
                <input class="form-check-input" type="radio" name="currencies_is_default" value="__position__" __isDefaultChecked__>
            </div>
            <div class="remove-item">
                <a href="#" class="text-danger text-decoration-none">
                    <x-core::icon name="ti ti-trash" />
                </a>
            </div>
        </li>
    </x-core::custom-template>
@endpush
