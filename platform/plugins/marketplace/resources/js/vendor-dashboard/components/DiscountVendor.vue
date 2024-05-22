<template>
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label">{{ __('discount.create_coupon_code') }}</label>

                        <div class="input-group input-group-flat">
                            <input
                                type="text"
                                class="form-control coupon-code-input"
                                name="code"
                                v-model="code"
                            />

                            <span class="input-group-text" v-if="generateUrl">
                                <a href="javascript:void(0)" @click="generateCouponCode($event)" class="input-group-link">{{ __('discount.generate_coupon_code') }}</a>
                            </span>
                        </div>

                        <small class="form-hint">
                            {{ __('discount.customers_will_enter_this_coupon_code_when_they_checkout') }}.
                        </small>
                    </div>

                    <div class="mb-3 position-relative">
                        <input
                            type="text"
                            class="form-control"
                            name="title"
                            v-model="title"
                            :placeholder="__('discount.enter_coupon_name')"
                        />
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_unlimited" v-model="is_unlimited" value="1">
                            <span class="form-check-label">
                                    {{ __('discount.unlimited_coupon') }}
                                </span>
                        </label>
                    </div>

                    <div class="mb-3 position-relative" v-show="!is_unlimited">
                        <label class="form-label">{{ __('discount.enter_number') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="quantity"
                            v-model="quantity"
                            autocomplete="off"
                            :disabled="is_unlimited"
                        />
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="display_at_checkout" v-model="display_at_checkout" value="1">
                            <span class="form-check-label">
                                {{ __('discount.display_at_checkout') }}
                            </span>
                            <span class="form-check-description">
                                {{ __('discount.display_at_checkout_description') }}
                            </span>
                        </label>
                    </div>

                    <div class="border-top">
                        <h4 class="mt-3 mb-2">{{ __('discount.coupon_type') }}</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <select
                                    id="discount-type-option"
                                    name="type_option"
                                    class="form-select"
                                    v-model="type_option"
                                    @change="handleChangeTypeOption()"
                                >
                                    <option v-for="(item, index) in type_options" :value="index" :key="index">
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group input-group-flat">
                                    <span class="input-group-text">{{ value_label }}</span>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="value"
                                        v-model="value"
                                        autocomplete="off"
                                        placeholder="0"
                                    />
                                    <span class="input-group-text">
                                        {{ discountUnit }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('discount.time') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label">{{ __('discount.start_date') }}</label>
                        <div class="d-flex">
                            <div class="input-icon datepicker">
                                <input
                                    type="text"
                                    :placeholder="dateFormat"
                                    :data-date-format="dateFormat"
                                    name="start_date"
                                    v-model="start_date"
                                    class="form-control rounded-end-0"
                                    readonly
                                    data-input
                                />
                                <span class="input-icon-addon">
                                    <i class="ti ti-calendar"></i>
                                </span>
                            </div>
                            <div class="input-icon">
                                <input
                                    type="text"
                                    placeholder="hh:mm"
                                    name="start_time"
                                    v-model="start_time"
                                    class="form-control rounded-start-0 timepicker timepicker-24"
                                />
                                <span class="input-icon-addon">
                                    <i class="icon ti ti-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">{{ __('discount.end_date') }}</label>
                        <div class="d-flex">
                            <div class="input-icon datepicker">
                                <input
                                    type="text"
                                    :placeholder="dateFormat"
                                    :data-date-format="dateFormat"
                                    name="end_date"
                                    v-model="end_date"
                                    class="form-control rounded-end-0"
                                    :disabled="unlimited_time"
                                    readonly
                                    data-input
                                />
                                <span class="input-icon-addon">
                                    <i class="ti ti-calendar"></i>
                                </span>
                            </div>
                            <div class="input-icon">
                                <input
                                    type="text"
                                    placeholder="hh:mm"
                                    name="end_time"
                                    v-model="end_time"
                                    class="form-control rounded-start-0 timepicker timepicker-24"
                                    :disabled="unlimited_time"
                                />
                                <span class="input-icon-addon">
                                    <i class="icon ti ti-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="position-relative">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="unlimited_time" v-model="unlimited_time" value="1">
                            <span class="form-check-label">{{ __('discount.never_expired') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="btn-list">
                        <button type="submit" class="btn btn-primary">{{ __('discount.save') }}</button>
                        <a class="btn me-2" :href="cancelUrl" v-if="cancelUrl">{{ __('discount.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.date-time-group {
    .invalid-feedback {
        position: absolute;
        bottom: -15px;
    }
}
</style>

<script>
const moment = require('moment')

export default {
    data: () => {
        return {
            title: null,
            code: null,
            type: 'coupon',
            is_unlimited: true,
            quantity: 0,
            unlimited_time: true,
            start_date: moment().format('Y-MM-DD'),
            start_time: '00:00',
            end_date: moment().format('Y-MM-DD'),
            end_time: '23:59',
            type_option: 'amount',
            value: null,
            target: 'all-orders',
            can_use_with_promotion: false,
            value_label: '',
            hidden_product_search_panel: true,
            product_collection_id: null,
            product_collections: [],
            discount_on: 'per-order',
            min_order_price: null,
            loading: false,
            discountUnit: '$',
            type_options: [],
            display_at_checkout: false,
        }
    },
    props: {
        currency: {
            type: String,
            default: () => null,
            required: true,
        },
        generateUrl: {
            type: String,
            default: () => null,
        },
        cancelUrl: {
            type: String,
            default: () => null,
        },
        dateFormat: {
            type: String,
            default: () => 'Y-m-d',
            required: true,
        },
    },
    mounted: function () {
        this.discountUnit = this.currency
        this.value_label = this.__('discount.discount')
        this.type_options = this.__('enums.typeOptions')
    },
    methods: {
        generateCouponCode: function (event) {
            event.preventDefault()
            let context = this
            axios
                .post(this.generateUrl)
                .then((res) => {
                    context.code = res.data.data
                    context.title = null
                    $('.coupon-code-input').closest('div').find('.invalid-feedback').remove()
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
        },
        handleChangeTypeOption: function () {
            let context = this

            context.discountUnit = this.currency
            context.value_label = this.__('discount.discount')

            switch (context.type_option) {
                case 'amount':
                    context.target = 'all-orders'
                    break
                case 'percentage':
                    context.target = 'all-orders'
                    context.discountUnit = '%'
                    break
                case 'shipping':
                    context.value_label = this.__('discount.when_shipping_fee_less_than')
                    break
                case 'same-price':
                    context.target = 'group-products'
                    context.value_label = this.__('discount.is')
                    context.getListProductCollections()
                    break
            }
        },
    },
}
</script>
