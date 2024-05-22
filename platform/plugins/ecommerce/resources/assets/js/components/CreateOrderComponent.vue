<template>
    <div class="row row-cards">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('order.order_information') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div :class="{ 'loading-skeleton': checking }" v-if="child_products.length">
                            <table class="table table-bordered table-vcenter">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{ __('order.product_name') }}</th>
                                        <th>{{ __('order.price') }}</th>
                                        <th width="90">{{ __('order.quantity') }}</th>
                                        <th>{{ __('order.total') }}</th>
                                        <th>{{ __('order.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(variant, vKey) in child_products" v-bind:key="`${variant.id}-${vKey}`">
                                        <td>
                                            <img :src="variant.image_url" :alt="variant.name" width="50" />
                                        </td>
                                        <td>
                                            <a :href="variant.product_link" target="_blank">{{ variant.name }}</a>
                                            <p v-if="variant.variation_attributes">
                                                <small>{{ variant.variation_attributes }}</small>
                                            </p>
                                            <ul
                                                v-if="
                                                    variant.option_values && Object.keys(variant.option_values).length
                                                "
                                            >
                                                <li>
                                                    <span>{{ __('order.price') }}:</span>
                                                    <span>{{ variant.original_price_label }}</span>
                                                </li>
                                                <li v-for="option in variant.option_values" v-bind:key="option.id">
                                                    <span>{{ option.title }}:</span>
                                                    <span v-for="value in option.values" v-bind:key="value.id">
                                                        {{ value.value }} <strong>+{{ value.price_label }}</strong>
                                                    </span>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <span>{{ variant.price_label }}</span>
                                        </td>
                                        <td class="text-center">
                                            <input
                                                class="form-control form-control-sm"
                                                :value="variant.select_qty"
                                                type="number"
                                                min="1"
                                                @input="handleChangeQuantity($event, variant, vKey)"
                                            />
                                        </td>
                                        <td>
                                            {{ variant.total_price_label }}
                                        </td>
                                        <td class="text-center">
                                            <a
                                                href="javascript:void(0)"
                                                @click="handleRemoveVariant($event, variant, vKey)"
                                                class="text-decoration-none"
                                            >
                                                <span class="icon-tabler-wrapper icon-sm icon-left">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-x"
                                                        width="24"
                                                        height="24"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                        stroke="currentColor"
                                                        fill="none"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M18 6l-12 12" />
                                                        <path d="M6 6l12 12" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="position-relative box-search-advance product">
                            <input
                                type="text"
                                class="form-control textbox-advancesearch product"
                                :placeholder="__('order.search_or_create_new_product')"
                                @click="loadListProductsAndVariations()"
                                @keyup="handleSearchProduct($event.target.value)"
                            />

                            <div
                                class="card position-absolute z-1 w-100"
                                :class="{ active: list_products, hidden: hidden_product_search_panel }"
                                :style="[loading ? { minHeight: '10rem' } : {}]"
                            >
                                <div v-if="loading" class="loading-spinner"></div>
                                <div v-else class="list-group list-group-flush overflow-auto" style="max-height: 25rem">
                                    <a
                                        href="javascript:void(0)"
                                        class="list-group-item list-group-item-action"
                                        v-ec-modal.add-product-item
                                    >
                                        <img
                                            width="28"
                                            src="/vendor/core/plugins/ecommerce/images/next-create-custom-line-item.svg"
                                            alt="icon"
                                            class="me-2"
                                        />
                                        {{ __('order.create_a_new_product') }}
                                    </a>
                                    <a
                                        v-for="product_item in list_products.data"
                                        :class="{
                                            'list-group-item list-group-item-action': true,
                                            'item-selectable': !product_item.variations.length,
                                            'item-not-selectable': product_item.variations.length,
                                        }"
                                        v-bind:key="product_item.id"
                                    >
                                        <div class="row align-items-start">
                                            <div class="col-auto">
                                                <span
                                                    class="avatar"
                                                    :style="{ backgroundImage: 'url(' + product_item.image_url + ')' }"
                                                ></span>
                                            </div>
                                            <div class="col text-truncate">
                                                <ProductAction
                                                    :ref="'product_actions_' + product_item.id"
                                                    :product="product_item"
                                                    @select-product="selectProductVariant"
                                                />

                                                <div
                                                    v-if="product_item.variations.length"
                                                    class="list-group list-group-flush"
                                                >
                                                    <div
                                                        class="list-group-item p-2"
                                                        v-for="variation in product_item.variations"
                                                        v-bind:key="variation.id"
                                                    >
                                                        <ProductAction
                                                            :product="variation"
                                                            @select-product="selectProductVariant"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="p-3" v-if="list_products.data && list_products.data.length === 0">
                                        <p class="text-muted text-center mb-0">{{ __('order.no_products_found') }}</p>
                                    </div>
                                </div>
                                <div
                                    class="card-footer"
                                    v-if="
                                        ((list_products.links && list_products.links.next) ||
                                            (list_products.links && list_products.links.prev)) &&
                                        !loading
                                    "
                                >
                                    <ul class="pagination my-0 d-flex justify-content-end">
                                        <li
                                            :class="{
                                                'page-item': true,
                                                disabled: list_products.meta.current_page === 1,
                                            }"
                                        >
                                            <span
                                                v-if="list_products.meta.current_page === 1"
                                                class="page-link"
                                                :aria-disabled="list_products.meta.current_page === 1"
                                            >
                                                <i class="icon ti ti-chevron-left"></i>
                                            </span>
                                            <a
                                                v-else
                                                href="javascript:void(0)"
                                                class="page-link"
                                                @click="
                                                    loadListProductsAndVariations(
                                                        list_products.links.prev
                                                            ? list_products.meta.current_page - 1
                                                            : list_products.meta.current_page,
                                                        true
                                                    )
                                                "
                                            >
                                                <i class="icon ti ti-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li :class="{ 'page-item': true, disabled: !list_products.links.next }">
                                            <span
                                                v-if="!list_products.links.next"
                                                class="page-link"
                                                :aria-disabled="!list_products.links.next"
                                            >
                                                <i class="icon ti ti-chevron-right"></i>
                                            </span>
                                            <a
                                                v-else
                                                href="javascript:void(0)"
                                                class="page-link"
                                                @click="
                                                    loadListProductsAndVariations(
                                                        list_products.links.next
                                                            ? list_products.meta.current_page + 1
                                                            : list_products.meta.current_page,
                                                        true
                                                    )
                                                "
                                            >
                                                <i class="icon ti ti-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 position-relative">
                                <label class="form-label" for="txt-note">{{ __('order.note') }}</label>
                                <textarea
                                    v-model="note"
                                    class="form-control textarea-auto-height"
                                    id="txt-note"
                                    rows="2"
                                    :placeholder="__('order.note_for_order')"
                                ></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <table class="table table-borderless text-end table-vcenter">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td width="120"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('order.sub_amount') }}</td>
                                        <td>
                                            <span
                                                v-if="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <span class="fw-bold">{{ child_sub_amount_label }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('order.tax_amount') }}</td>
                                        <td>
                                            <span
                                                v-if="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <span class="fw-bold">{{ child_tax_amount_label }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('order.promotion_discount_amount') }}</td>
                                        <td>
                                            <span
                                                v-show="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <span :class="{ 'fw-bold': true, 'text-success': child_promotion_amount }">
                                                {{ child_promotion_amount_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button
                                                type="button"
                                                v-ec-modal.add-discounts
                                                class="btn btn-outline-primary btn-sm mb-1"
                                            >
                                                <template v-if="!has_applied_discount">
                                                    <i class="icon-sm ti ti-plus"></i>
                                                    {{ __('order.add_discount') }}
                                                </template>
                                                <template v-else>{{ __('order.discount') }}</template>
                                            </button>
                                            <span class="d-block small fw-bold" v-if="has_applied_discount">
                                                {{ child_coupon_code || child_discount_description }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                v-show="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <span :class="{ 'text-success fw-bold': child_discount_amount }">
                                                {{ child_discount_amount_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="is_available_shipping">
                                        <td>
                                            <button
                                                type="button"
                                                v-ec-modal.add-shipping
                                                class="btn btn-outline-primary btn-sm mb-1"
                                            >
                                                <template v-if="!child_is_selected_shipping">
                                                    <i class="icon-sm ti ti-plus"></i>
                                                    {{ __('order.add_shipping_fee') }}
                                                </template>
                                                <template v-else>{{ __('order.shipping') }}</template>
                                            </button>
                                            <span class="d-block small fw-bold" v-if="child_shipping_method_name">
                                                {{ child_shipping_method_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                v-show="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <span :class="{ 'fw-bold': child_shipping_amount }">
                                                {{ child_shipping_amount_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('order.total_amount') }}</td>
                                        <td>
                                            <span
                                                v-show="checking"
                                                class="spinner-grow spinner-grow-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                            <h4 class="d-inline-block">{{ child_total_amount_label }}</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label for="payment-method" class="form-label">{{
                                                __('order.payment_method')
                                            }}</label>
                                            <select
                                                class="form-select"
                                                id="payment-method"
                                                v-model="child_payment_method"
                                            >
                                                <option value="cod">
                                                    {{ __('order.cash_on_delivery_cod') }}
                                                </option>
                                                <option value="bank_transfer">
                                                    {{ __('order.bank_transfer') }}
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <p class="mb-0 text-uppercase">
                            <i class="icon ti ti-credit-card text-primary"></i>
                            {{ __('order.confirm_payment_and_create_order') }}
                        </p>
                        <div class="btn-list">
                            <button
                                class="btn btn-success"
                                v-ec-modal.make-paid
                                :disabled="
                                    (!child_product_ids.length || child_payment_method === 'cod') &&
                                    child_total_amount !== 0
                                "
                            >
                                {{ __('order.paid') }}
                            </button>
                            <button
                                class="btn btn-primary"
                                v-ec-modal.make-pending
                                :disabled="!child_product_ids.length || child_total_amount === 0"
                            >
                                {{ __('order.pay_later') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div v-if="!child_customer_id || !child_customer">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('order.customer_information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="position-relative box-search-advance customer">
                            <input
                                type="text"
                                class="form-control textbox-advancesearch customer"
                                @click="loadListCustomersForSearch()"
                                @keyup="handleSearchCustomer($event.target.value)"
                                :placeholder="__('order.search_or_create_new_customer')"
                            />

                            <div
                                class="card position-absolute w-100 z-1"
                                :class="{ active: customers, hidden: hidden_customer_search_panel }"
                                :style="[loading ? { minHeight: '10rem' } : {}]"
                            >
                                <div v-if="loading" class="loading-spinner"></div>
                                <div v-else class="list-group list-group-flush overflow-auto" style="max-height: 25rem">
                                    <div class="list-group-item cursor-pointer" v-ec-modal.add-customer>
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img
                                                    width="28"
                                                    src="/vendor/core/plugins/ecommerce/images/next-create-customer.svg"
                                                    alt="icon"
                                                />
                                            </div>
                                            <div class="col">
                                                <span>{{ __('order.create_new_customer') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a
                                        class="list-group-item list-group-item-action"
                                        href="javascript:void(0)"
                                        v-for="customer in customers.data"
                                        v-bind:key="customer.id"
                                        @click="selectCustomer(customer)"
                                    >
                                        <div class="flexbox-grid-default flexbox-align-items-center">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span
                                                        class="avatar"
                                                        :style="{ backgroundImage: 'url(' + customer.avatar_url + ')' }"
                                                    ></span>
                                                </div>
                                                <div class="col text-truncate">
                                                    <div class="text-body d-block">{{ customer.name }}</div>
                                                    <a
                                                        :href="'mailto:' + customer.email"
                                                        class="text-secondary text-truncate mt-n1"
                                                        >{{ customer.email || '-' }}</a
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="list-group-item" v-if="customers.data && customers.data.length === 0">
                                        {{ __('order.no_customer_found') }}
                                    </div>
                                </div>
                                <div
                                    class="card-footer"
                                    v-if="(customers.next_page_url || customers.prev_page_url) && !loading"
                                >
                                    <ul class="pagination my-0 d-flex justify-content-end">
                                        <li :class="{ 'page-item': true, disabled: customers.current_page === 1 }">
                                            <span
                                                v-if="customers.current_page === 1"
                                                class="page-link"
                                                :aria-disabled="customers.current_page === 1"
                                            >
                                                <i class="icon ti ti-chevron-left"></i>
                                            </span>
                                            <a
                                                v-else
                                                href="javascript:void(0)"
                                                class="page-link"
                                                @click="
                                                    loadListCustomersForSearch(
                                                        customers.prev_page_url
                                                            ? customers.current_page - 1
                                                            : customers.current_page,
                                                        true
                                                    )
                                                "
                                            >
                                                <i class="icon ti ti-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li :class="{ 'page-item': true, disabled: !customers.next_page_url }">
                                            <span
                                                v-if="!customers.next_page_url"
                                                class="page-link"
                                                :aria-disabled="!customers.next_page_url"
                                            >
                                                <i class="icon ti ti-chevron-right"></i>
                                            </span>
                                            <a
                                                v-else
                                                href="javascript:void(0)"
                                                class="page-link"
                                                @click="
                                                    loadListCustomersForSearch(
                                                        customers.next_page_url
                                                            ? customers.current_page + 1
                                                            : customers.current_page,
                                                        true
                                                    )
                                                "
                                            >
                                                <i class="icon ti ti-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="child_customer_id && child_customer">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('order.customer') }}</h4>
                        <div class="card-actions">
                            <button
                                type="button"
                                data-bs-toggle="tooltip"
                                data-placement="top"
                                title="Delete customer"
                                @click="removeCustomer()"
                                class="btn-action"
                            >
                                <span class="icon-tabler-wrapper icon-sm icon-left">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-x"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M18 6l-12 12" />
                                        <path d="M6 6l12 12" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3">
                            <div class="mb-3">
                                <span
                                    class="avatar avatar-lg avatar-rounded"
                                    :style="{ backgroundImage: `url(${child_customer.avatar_url || child_customer.avatar})`}"
                                ></span>
                            </div>

                            <div class="mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                                {{ child_customer_order_numbers }}
                                {{ __('order.orders') }}
                            </div>

                            <div class="mb-n1">{{ child_customer.name }}</div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a :href="`mailto:${child_customer.email}`">
                                    {{ child_customer.email || '-' }}
                                </a>

                                <a
                                    href="javascript:void(0)"
                                    v-ec-modal.edit-email
                                    data-placement="top"
                                    data-bs-toggle="tooltip"
                                    data-bs-original-title="Edit email"
                                    class="btn-action text-decoration-none"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </a>
                            </div>
                        </div>

                        <template v-if="is_available_shipping">
                            <div class="hr my-1"></div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">{{ __('order.shipping_address') }}</h4>
                                    <button
                                        v-ec-modal.edit-address
                                        type="button"
                                        class="btn-action"
                                        data-bs-toggle="tooltip"
                                        data-bs-title="Update address"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    </button>
                                </div>

                                <div v-if="child_customer_addresses.length > 1" class="mb-3">
                                    <select class="form-select" @change="selectCustomerAddress($event)">
                                        <option
                                            v-for="address_item in child_customer_addresses"
                                            :value="address_item.id"
                                            :selected="address_item.id === customer_address.id"
                                            v-bind:key="address_item.id"
                                        >
                                            {{ address_item.full_address }}
                                        </option>
                                    </select>
                                </div>

                                <dl class="row mb-0">
                                    <dd>{{ child_customer_address.name }}</dd>
                                    <dd>{{ child_customer_address.phone }}</dd>
                                    <dd>
                                        <a :href="'mailto:' + child_customer_address.email">
                                            {{ child_customer_address.email }}
                                        </a>
                                    </dd>
                                    <dd>{{ child_customer_address.address }}</dd>
                                    <dd>{{ child_customer_address.city_name }}</dd>
                                    <dd>{{ child_customer_address.state_name }}</dd>
                                    <dd>{{ child_customer_address.country_name }}</dd>
                                    <dd v-if="zip_code_enabled">{{ child_customer_address.zip_code }}</dd>
                                    <dd v-if="child_customer_address.full_address">
                                        <a
                                            target="_blank"
                                            class="hover-underline"
                                            :href="'https://maps.google.com/?q=' + child_customer_address.full_address"
                                        >{{ __('order.see_on_maps') }}</a
                                        >
                                    </dd>
                                </dl>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <AddProductModal @create-product="createProduct" :store="store"></AddProductModal>

        <ec-modal
            id="add-discounts"
            title="Add discount"
            :ok-title="__('order.add_discount')"
            :cancel-title="__('order.close')"
            @ok="handleAddDiscount($event)"
        >
            <div class="next-form-section">
                <div class="next-form-grid">
                    <div class="mb-3 position-relative">
                        <label class="form-label">{{ __('order.discount_based_on') }}</label>
                        <div class="row">
                            <div class="col-auto">
                                <button
                                    value="amount"
                                    class="btn btn-active"
                                    :class="{ active: discount_type === 'amount' }"
                                    @click="changeDiscountType($event)"
                                >
                                    {{ currency || '$' }}
                                </button>&nbsp;
                                <button
                                    value="percentage"
                                    class="btn btn-active"
                                    :class="{ active: discount_type === 'percentage' }"
                                    @click="changeDiscountType($event)"
                                >
                                    %
                                </button>
                            </div>
                            <div class="col">
                                <div class="input-group input-group-flat">
                                    <input class="form-control" v-model="discount_custom_value" />
                                    <span class="input-group-text">{{ discount_type_unit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="next-form-grid">
                    <div class="mb-3 position-relative">
                        <label class="form-label">{{ __('order.or_coupon_code') }}</label>
                        <input class="form-control coupon-code-input" v-model="child_coupon_code" />
                    </div>
                    <div class="position-relative">
                        <label class="form-label">{{ __('order.description') }}</label>
                        <input
                            :placeholder="__('order.discount_description')"
                            class="form-control"
                            v-model="child_discount_description"
                        />
                    </div>
                </div>
            </div>
        </ec-modal>

        <ec-modal
            id="add-shipping"
            :title="__('order.shipping_fee')"
            :ok-title="__('order.update')"
            :cancel-title="__('order.close')"
            @ok="selectShippingMethod($event)"
        >
            <div v-if="!child_products.length || !child_customer_address.phone">
                <div class="alert alert-success" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="icon alert-icon ti ti-alert-circle" />
                        </div>
                        <div>
                            <h4 class="alert-title">{{ __('order.how_to_select_configured_shipping') }}</h4>
                            <div class="text-muted">
                                {{ __('order.please_products_and_customer_address_to_see_the_shipping_rates') }}.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="position-relative">
                <label class="form-check form-check-inline">
                    <input
                        type="radio"
                        class="form-check-input"
                        value="free-shipping"
                        name="shipping_type"
                        v-model="shipping_type"
                    />
                    {{ __('order.free_shipping') }}
                </label>
            </div>

            <div v-if="child_products.length && child_customer_address.phone">
                <div class="mb-3 position-relative">
                    <label class="form-check form-check-inline">
                        <input
                            type="radio"
                            class="form-check-input"
                            value="custom"
                            name="shipping_type"
                            v-model="shipping_type"
                            :disabled="shipping_methods && !Object.keys(shipping_methods).length"
                        />
                        <span class="form-check-label">{{ __('order.custom') }}</span>
                        <small class="text-warning" v-if="shipping_methods && !Object.keys(shipping_methods).length">
                            {{ __('order.shipping_method_not_found') }}
                        </small>
                    </label>
                </div>

                <select class="form-select" v-show="shipping_type === 'custom'">
                    <option
                        v-for="(shipping, shipping_key) in shipping_methods"
                        :value="shipping_key"
                        :selected="shipping_key === `${child_shipping_method};${child_shipping_option}`"
                        v-bind:key="shipping_key"
                        :data-shipping-method="shipping.method"
                        :data-shipping-option="shipping.option"
                    >
                        {{ shipping.title }}
                    </option>
                </select>
            </div>
        </ec-modal>

        <ec-modal
            id="make-paid"
            :title="__('order.confirm_payment_is_paid_for_this_order')"
            :ok-title="__('order.create_order')"
            :cancel-title="__('order.close')"
            @ok="createOrder($event, true)"
        >
            <div class="alert alert-warning" role="alert">
                {{
                    __(
                        'order.payment_status_of_the_order_is_paid_once_the_order_has_been_created_you_cannot_change_the_payment_method_or_status'
                    )
                }}.
            </div>

            <div>
                <span>{{ __('order.paid_amount') }}:</span>
                <h3 class="d-inline-block ms-2 mb-0">{{ child_total_amount_label }}</h3>
            </div>
        </ec-modal>

        <ec-modal
            id="make-pending"
            :title="__('order.confirm_that_payment_for_this_order_will_be_paid_later')"
            :ok-title="__('order.create_order')"
            :cancel-title="__('order.close')"
            @ok="createOrder($event)"
        >
            <div class="alert alert-warning" role="alert">
                {{
                    __(
                        'order.payment_status_of_the_order_is_pending_once_the_order_has_been_created_you_cannot_change_the_payment_method_or_status'
                    )
                }}.
            </div>

            <div>
                <span>{{ __('order.pending_amount') }}:</span>
                <h3 class="d-inline-block ms-2 mb-0">{{ child_total_amount_label }}</h3>
            </div>
        </ec-modal>

        <OrderCustomerAddress
            :customer="child_customer"
            :address="child_customer_address"
            :zip_code_enabled="zip_code_enabled"
            :use_location_data="use_location_data"
            @update-order-address="updateOrderAddress"
            @update-customer-email="updateCustomerEmail"
            @create-new-customer="createNewCustomer"
        ></OrderCustomerAddress>
    </div>
</template>

<script>
import ProductAction from './partials/ProductActionComponent.vue'
import OrderCustomerAddress from './partials/OrderCustomerAddressComponent.vue'
import AddProductModal from './partials/AddProductModalComponent.vue'

export default {
    props: {
        products: {
            type: Array,
            default: () => [],
        },
        product_ids: {
            type: Array,
            default: () => [],
        },
        customer_id: {
            type: Number,
            default: () => null,
        },
        customer: {
            type: Object,
            default: () => ({
                email: 'guest@example.com',
            }),
        },
        customer_addresses: {
            type: Array,
            default: () => [],
        },
        customer_address: {
            type: Object,
            default: () => ({
                name: null,
                email: null,
                address: null,
                phone: null,
                country: null,
                state: null,
                city: null,
                zip_code: null,
            }),
        },
        customer_order_numbers: {
            type: Number,
            default: () => 0,
        },
        sub_amount: {
            type: Number,
            default: () => 0,
        },
        sub_amount_label: {
            type: String,
            default: () => '',
        },
        tax_amount: {
            type: Number,
            default: () => 0,
        },
        tax_amount_label: {
            type: String,
            default: () => '',
        },
        total_amount: {
            type: Number,
            default: () => 0,
        },
        total_amount_label: {
            type: String,
            default: () => '',
        },
        coupon_code: {
            type: String,
            default: () => '',
        },
        promotion_amount: {
            type: Number,
            default: () => 0,
        },
        promotion_amount_label: {
            type: String,
            default: () => '',
        },
        discount_amount: {
            type: Number,
            default: () => 0,
        },
        discount_amount_label: {
            type: String,
            default: () => '',
        },
        discount_description: {
            type: String,
            default: () => null,
        },
        shipping_amount: {
            type: Number,
            default: () => 0,
        },
        shipping_amount_label: {
            type: String,
            default: () => '',
        },
        shipping_method: {
            type: String,
            default: () => 'default',
        },
        shipping_option: {
            type: String,
            default: () => '',
        },
        is_selected_shipping: {
            type: Boolean,
            default: () => false,
        },
        shipping_method_name: {
            type: String,
            default: function () {
                return 'order.free_shipping'
            },
        },
        payment_method: {
            type: String,
            default: () => 'cod',
        },
        currency: {
            type: String,
            default: () => null,
            required: true,
        },
        zip_code_enabled: {
            type: Number,
            default: () => 0,
            required: true,
        },
        use_location_data: {
            type: Number,
            default: () => 0,
        },
        is_tax_enabled: {
            type: Number,
            default: () => true,
        },
    },
    data: function () {
        return {
            list_products: {
                data: [],
            },
            hidden_product_search_panel: true,
            loading: false,
            checking: false,
            note: null,
            customers: {
                data: [],
            },
            hidden_customer_search_panel: true,
            customer_keyword: null,
            shipping_type: 'free-shipping',
            shipping_methods: {},
            discount_type_unit: this.currency,
            discount_type: 'amount',
            child_discount_description: this.discount_description,
            has_invalid_coupon: false,
            has_applied_discount: this.discount_amount > 0,
            discount_custom_value: 0,
            child_coupon_code: this.coupon_code,
            child_customer: this.customer,
            child_customer_id: this.customer_id,
            child_customer_order_numbers: this.customer_order_numbers,
            child_customer_addresses: this.customer_addresses,
            child_customer_address: this.customer_address,
            child_products: this.products,
            child_product_ids: this.product_ids,
            child_sub_amount: this.sub_amount,
            child_sub_amount_label: this.sub_amount_label,
            child_tax_amount: this.tax_amount,
            child_tax_amount_label: this.tax_amount_label,
            child_total_amount: this.total_amount,
            child_total_amount_label: this.total_amount_label,
            child_promotion_amount: this.promotion_amount,
            child_promotion_amount_label: this.promotion_amount_label,
            child_discount_amount: this.discount_amount,
            child_discount_amount_label: this.discount_amount_label,
            child_shipping_amount: this.shipping_amount,
            child_shipping_amount_label: this.shipping_amount_label,
            child_shipping_method: this.shipping_method,
            child_shipping_option: this.shipping_option,
            child_shipping_method_name: this.shipping_method_name,
            child_is_selected_shipping: this.is_selected_shipping,
            child_payment_method: this.payment_method,
            productSearchRequest: null,
            timeoutProductRequest: null,
            customerSearchRequest: null,
            checkDataOrderRequest: null,
            store: {
                id: 0,
                name: null,
            },
            is_available_shipping: false,
        }
    },
    components: {
        ProductAction,
        OrderCustomerAddress,
        AddProductModal,
    },
    mounted: function () {
        let context = this
        $(document).on('click', 'body', (e) => {
            let container = $('.box-search-advance')

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                context.hidden_customer_search_panel = true
                context.hidden_product_search_panel = true
            }
        })

        if (context.product_ids) {
            context.checkDataBeforeCreateOrder()
        }
    },
    methods: {
        loadListCustomersForSearch: function (page = 1, force = false) {
            let context = this
            context.hidden_customer_search_panel = false
            $('.textbox-advancesearch.customer')
                .closest('.box-search-advance.customer')
                .find('.panel')
                .addClass('active')
            if (_.isEmpty(context.customers.data) || force) {
                context.loading = true
                if (context.customerSearchRequest) {
                    context.customerSearchRequest.abort()
                }

                context.customerSearchRequest = new AbortController()

                axios
                    .get(
                        route('customers.get-list-customers-for-search', {
                            keyword: context.customer_keyword,
                            page: page,
                        }),
                        { signal: context.customerSearchRequest.signal }
                    )
                    .then((res) => {
                        context.customers = res.data.data
                        context.loading = false
                    })
                    .catch((error) => {
                        if (!axios.isCancel(error)) {
                            context.loading = false
                            Botble.handleError(error.response.data)
                        }
                    })
            }
        },
        handleSearchCustomer: function (value) {
            if (value !== this.customer_keyword) {
                let context = this
                this.customer_keyword = value
                setTimeout(() => {
                    context.loadListCustomersForSearch(1, true)
                }, 500)
            }
        },
        loadListProductsAndVariations: function (page = 1, force = false, show_panel = true) {
            let context = this
            if (show_panel) {
                context.hidden_product_search_panel = false
                $('.textbox-advancesearch.product')
                    .closest('.box-search-advance.product')
                    .find('.panel')
                    .addClass('active')
            } else {
                context.hidden_product_search_panel = true
            }

            if (_.isEmpty(context.list_products.data) || force) {
                context.loading = true
                if (context.productSearchRequest) {
                    context.productSearchRequest.abort()
                }

                context.productSearchRequest = new AbortController()

                axios
                    .get(
                        route('products.get-all-products-and-variations', {
                            keyword: context.product_keyword,
                            page: page,
                            product_ids: context.child_product_ids,
                        }),
                        { signal: context.productSearchRequest.signal }
                    )
                    .then((res) => {
                        context.list_products = res.data.data
                        context.loading = false
                    })
                    .catch((error) => {
                        if (!axios.isCancel(error)) {
                            Botble.handleError(error.response.data)
                            context.loading = false
                        }
                    })
            }
        },
        handleSearchProduct: function (value) {
            if (value !== this.product_keyword) {
                let context = this
                context.product_keyword = value
                if (context.timeoutProductRequest) {
                    clearTimeout(context.timeoutProductRequest)
                }

                context.timeoutProductRequest = setTimeout(() => {
                    context.loadListProductsAndVariations(1, true)
                }, 1000)
            }
        },
        selectProductVariant: function (product, refOptions) {
            let context = this
            if (_.isEmpty(product) && product.is_out_of_stock) {
                Botble.showError(context.__('order.cant_select_out_of_stock_product'))
                return false
            }
            const requiredOptions = product.product_options.filter((item) => item.required)

            if (product.is_variation || !product.variations.length) {
                let refAction = context.$refs['product_actions_' + product.original_product_id][0]
                refOptions = refAction.$refs['product_options_' + product.original_product_id]
            }

            let productOptions = refOptions.values

            if (requiredOptions.length) {
                let errorMessage = []
                requiredOptions.forEach((item) => {
                    if (!productOptions[item.id]) {
                        errorMessage.push(context.__('order.please_choose_product_option') + ': ' + item.name)
                    }
                })

                if (errorMessage && errorMessage.length) {
                    errorMessage.forEach((message) => {
                        Botble.showError(message)
                    })
                    return
                }
            }

            let options = []

            product.product_options.map((item) => {
                options[item.id] = {
                    option_type: item.option_type,
                    values: productOptions[item.id],
                }
            })
            context.child_products.push({ id: product.id, quantity: 1, options })
            context.checkDataBeforeCreateOrder()

            context.hidden_product_search_panel = true
        },
        selectCustomer: function (customer) {
            this.child_customer = customer
            this.child_customer_id = customer.id

            this.loadCustomerAddress(this.child_customer_id)

            this.getOrderNumbers()
        },
        checkDataBeforeCreateOrder: function (data = {}, onSuccess = null, onError = null) {
            let context = this
            let formData = { ...context.getOrderFormData(), ...data }

            context.checking = true
            if (context.checkDataOrderRequest) {
                context.checkDataOrderRequest.abort()
            }

            context.checkDataOrderRequest = new AbortController()

            axios
                .post(route('orders.check-data-before-create-order'), formData, {
                    signal: context.checkDataOrderRequest.signal,
                })
                .then((res) => {
                    let data = res.data.data

                    if (data.update_context_data) {
                        context.child_products = data.products
                        context.child_product_ids = _.map(data.products, 'id')

                        context.child_sub_amount = data.sub_amount
                        context.child_sub_amount_label = data.sub_amount_label

                        context.child_tax_amount = data.tax_amount
                        context.child_tax_amount_label = data.tax_amount_label

                        context.child_promotion_amount = data.promotion_amount
                        context.child_promotion_amount_label = data.promotion_amount_label

                        context.child_discount_amount = data.discount_amount
                        context.child_discount_amount_label = data.discount_amount_label

                        context.child_shipping_amount = data.shipping_amount
                        context.child_shipping_amount_label = data.shipping_amount_label

                        context.child_total_amount = data.total_amount
                        context.child_total_amount_label = data.total_amount_label

                        context.shipping_methods = data.shipping_methods

                        context.child_shipping_method_name = data.shipping_method_name
                        context.child_shipping_method = data.shipping_method
                        context.child_shipping_option = data.shipping_option
                        context.is_available_shipping = data.is_available_shipping

                        context.store = data.store && data.store.id ? data.store : { id: 0, name: null }
                    }

                    if (res.data.error) {
                        Botble.showError(res.data.message)
                        if (onError) {
                            onError()
                        }
                    } else {
                        if (onSuccess) {
                            onSuccess()
                        }
                    }
                    context.checking = false
                })
                .catch((error) => {
                    if (!axios.isCancel(error)) {
                        context.checking = false
                        Botble.handleError(error.response.data)
                    }
                })
        },
        getOrderFormData: function () {
            let products = []
            _.each(this.child_products, function (item) {
                products.push({
                    id: item.id,
                    quantity: item.select_qty,
                    options: item.options,
                })
            })

            return {
                products,
                payment_method: this.child_payment_method,
                shipping_method: this.child_shipping_method,
                shipping_option: this.child_shipping_option,
                shipping_amount: this.child_shipping_amount,
                discount_amount: this.child_discount_amount,
                discount_description: this.child_discount_description,
                coupon_code: this.child_coupon_code,
                customer_id: this.child_customer_id,
                note: this.note,
                sub_amount: this.child_sub_amount,
                tax_amount: this.child_tax_amount,
                amount: this.child_total_amount,
                customer_address: this.child_customer_address,
                discount_type: this.discount_type,
                discount_custom_value: this.discount_custom_value,
                shipping_type: this.shipping_type,
            }
        },
        removeCustomer: function () {
            this.child_customer = this.customer
            this.child_customer_id = null
            this.child_customer_addresses = []
            this.child_customer_address = {
                name: null,
                email: null,
                address: null,
                phone: null,
                country: null,
                state: null,
                city: null,
                zip_code: null,
                full_address: null,
            }
            this.child_customer_order_numbers = 0

            this.checkDataBeforeCreateOrder()
        },
        handleRemoveVariant: function (event, variant, vKey) {
            event.preventDefault()
            this.child_product_ids = this.child_product_ids.filter((item, k) => k !== vKey)
            this.child_products = this.child_products.filter((item, k) => k !== vKey)

            this.checkDataBeforeCreateOrder()
        },
        createOrder: function (event, paid = false) {
            event.preventDefault()
            $(event.target).addClass('btn-loading')

            let formData = this.getOrderFormData()
            formData.payment_status = paid ? 'completed' : 'pending'

            axios
                .post(route('orders.create'), formData)
                .then((res) => {
                    let data = res.data.data
                    if (res.data.error) {
                        Botble.showError(res.data.message)
                    } else {
                        Botble.showSuccess(res.data.message)
                        if (paid) {
                            $event.emit('ec-modal:close', 'make-paid')
                        } else {
                            $event.emit('ec-modal:close', 'make-pending')
                        }

                        setTimeout(() => {
                            window.location.href = route('orders.edit', data.id)
                        }, 1000)
                    }
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
                .then(() => {
                    $(event.target).removeClass('btn-loading')
                })
        },
        createProduct: function (event, product) {
            event.preventDefault()
            $(event.target).addClass('btn-loading')
            let context = this
            if (context.store && context.store.id) {
                product.store_id = context.store.id
            }

            axios
                .post(route('products.create-product-when-creating-order'), product)
                .then((res) => {
                    if (res.data.error) {
                        Botble.showError(res.data.message)
                    } else {
                        context.product = res.data.data

                        context.list_products = {
                            data: [],
                        }

                        let productItem = context.product
                        productItem.select_qty = 1

                        context.child_products.push(productItem)
                        context.child_product_ids.push(context.product.id)

                        context.hidden_product_search_panel = true

                        Botble.showSuccess(res.data.message)

                        $event.emit('ec-modal:close', 'add-product-item')

                        context.checkDataBeforeCreateOrder()
                    }
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
                .then(() => {
                    $(event.target).removeClass('btn-loading')
                })
        },
        updateCustomerEmail: function (event) {
            event.preventDefault()

            $(event.target).addClass('btn-loading')

            let context = this

            axios
                .post(route('customers.update-email', context.child_customer_address.id), {
                    email: context.child_customer.email,
                })
                .then(({ data }) => {
                    if (data.error) {
                        Botble.showError(data.message)
                    } else {
                        Botble.showSuccess(data.message)

                        $event.emit('ec-modal:close', 'edit-email')
                    }
                })
                .catch(({ response }) => {
                    Botble.handleError(response.data)
                })
                .then(() => {
                    $(event.target).removeClass('btn-loading')
                })
        },
        updateOrderAddress: function (event) {
            event.preventDefault()

            if (this.customer) {
                $(event.target).addClass('btn-loading')

                this.checkDataBeforeCreateOrder(
                    {},
                    () => {
                        setTimeout(() => {
                            $(event.target).removeClass('btn-loading')
                            $event.emit('ec-modal:close', 'edit-address')
                        }, 500)
                    },
                    () => {
                        setTimeout(() => {
                            $(event.target).removeClass('btn-loading')
                        }, 500)
                    }
                )
            }
        },
        createNewCustomer: function (event) {
            event.preventDefault()
            let context = this

            $(event.target).addClass('btn-loading')

            axios
                .post(route('customers.create-customer-when-creating-order'), {
                    customer_id: context.child_customer_id,
                    name: context.child_customer_address.name,
                    email: context.child_customer_address.email,
                    phone: context.child_customer_address.phone,
                    address: context.child_customer_address.address,
                    country: (context.child_customer_address.country ? context.child_customer_address.country.toString() : ''),
                    state: (context.child_customer_address.state ? context.child_customer_address.state.toString() : ''),
                    city: (context.child_customer_address.city ? context.child_customer_address.city.toString() : ''),
                    zip_code: context.child_customer_address.zip_code,
                })
                .then((res) => {
                    if (res.data.error) {
                        Botble.showError(res.data.message)
                    } else {
                        context.child_customer_address = res.data.data.address
                        context.child_customer = res.data.data.customer
                        context.child_customer_id = context.child_customer.id

                        context.customers = {
                            data: [],
                        }

                        Botble.showSuccess(res.data.message)
                        context.checkDataBeforeCreateOrder()

                        $event.emit('ec-modal:close', 'add-customer')
                    }
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
                .then(() => {
                    $(event.target).removeClass('btn-loading')
                })
        },
        selectCustomerAddress: function (event) {
            let context = this
            _.each(this.child_customer_addresses, (item) => {
                if (parseInt(item.id) === parseInt(event.target.value)) {
                    context.child_customer_address = item
                }
            })

            this.checkDataBeforeCreateOrder()
        },
        getOrderNumbers: function () {
            let context = this
            axios
                .get(route('customers.get-customer-order-numbers', context.child_customer_id))
                .then((res) => {
                    context.child_customer_order_numbers = res.data.data
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
        },
        loadCustomerAddress: function () {
            let context = this
            axios
                .get(route('customers.get-customer-addresses', context.child_customer_id))
                .then((res) => {
                    context.child_customer_addresses = res.data.data
                    if (!_.isEmpty(context.child_customer_addresses)) {
                        context.child_customer_address = _.first(context.child_customer_addresses)
                    }
                    this.checkDataBeforeCreateOrder()
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
        },
        selectShippingMethod: function (event) {
            event.preventDefault()
            let context = this
            let $button = $(event.target).find('.btn-primary')
            $button.addClass('btn-loading')

            context.child_is_selected_shipping = true

            if (context.shipping_type === 'free-shipping') {
                context.child_shipping_method_name = context.__('order.free_shipping')
                context.child_shipping_amount = 0
            } else {
                let selected_shipping = $(event.target).find('.ui-select').val()
                if (!_.isEmpty(selected_shipping)) {
                    let option = $(event.target).find('.ui-select option:selected')
                    context.child_shipping_method = option.data('shipping-method')
                    context.child_shipping_option = option.data('shipping-option')
                }
            }

            this.checkDataBeforeCreateOrder(
                {},
                () => {
                    setTimeout(function () {
                        $button.removeClass('btn-loading')
                        $event.emit('ec-modal:close', 'add-shipping')
                    }, 500)
                },
                () => {
                    setTimeout(function () {
                        $button.removeClass('btn-loading')
                    }, 500)
                }
            )
        },
        changeDiscountType: function (event) {
            if ($(event.target).val() === 'amount') {
                this.discount_type_unit = this.currency
            } else {
                this.discount_type_unit = '%'
            }
            this.discount_type = $(event.target).val()
        },
        handleAddDiscount: function (event) {
            event.preventDefault()
            let $target = $(event.target)
            let context = this

            context.has_applied_discount = true
            context.has_invalid_coupon = false

            let $button = $target.find('.btn-primary')

            $button.addClass('btn-loading').prop('disabled', true)

            if (context.child_coupon_code) {
                context.discount_custom_value = 0
            } else {
                context.discount_custom_value = Math.max(parseFloat(context.discount_custom_value), 0)
                if (context.discount_type === 'percentage') {
                    context.discount_custom_value = Math.min(context.discount_custom_value, 100)
                }
            }

            context.checkDataBeforeCreateOrder(
                {},
                () => {
                    setTimeout(function () {
                        if (!context.child_coupon_code && !context.discount_custom_value) {
                            context.has_applied_discount = false
                        }
                        $button.removeClass('btn-loading').prop('disabled', false)
                        $event.emit('ec-modal:close', 'add-discounts')
                    }, 500)
                },
                () => {
                    if (context.child_coupon_code) {
                        context.has_invalid_coupon = true
                    }
                    $button.removeClass('btn-loading').prop('disabled', false)
                }
            )
        },
        handleChangeQuantity: function (event, variant, vKey) {
            event.preventDefault()
            let context = this
            variant.select_qty = parseInt(event.target.value)

            _.each(context.child_products, function (item, key) {
                if (vKey === key) {
                    if (variant.with_storehouse_management && parseInt(variant.select_qty) > variant.quantity) {
                        variant.select_qty = variant.quantity
                    }
                    context.child_products[key] = variant
                }
            })

            if (context.timeoutChangeQuantity) {
                clearTimeout(context.timeoutChangeQuantity)
            }

            context.timeoutChangeQuantity = setTimeout(() => {
                context.checkDataBeforeCreateOrder()
            }, 1500)
        },
    },
    watch: {
        child_payment_method: function () {
            this.checkDataBeforeCreateOrder()
        },
    },
}
</script>
