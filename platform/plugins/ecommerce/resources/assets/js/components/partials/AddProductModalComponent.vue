<template>
    <ec-modal
        id="add-product-item"
        :title="__('order.add_product')"
        :ok-title="__('order.save')"
        :cancel-title="__('order.cancel')"
        @shown="resetProductData()"
        @ok="$emit('create-product', $event, product)"
    >
        <div class="mb-3 position-relative">
            <label class="form-label">{{ __('order.name') }}</label>
            <input type="text" class="form-control" v-model="product.name" />
        </div>
        <div class="row">
            <div class="col-6 mb-3 position-relative">
                <label class="form-label">{{ __('order.price') }}</label>
                <input type="text" class="form-control" v-model="product.price" />
            </div>
            <div class="col-6 mb-3 position-relative">
                <label class="form-label">{{ __('order.sku_optional') }}</label>
                <input type="text" class="form-control" v-model="product.sku" />
            </div>
        </div>
        <div :class="{ 'position-relative': true, 'mb-3': product.with_storehouse_management || store && store.id }">
            <label class="form-check">
                <input
                    type="checkbox"
                    class="form-check-input"
                    v-model="product.with_storehouse_management"
                    value="1"
                />
                <span class="form-check-label">{{ __('order.with_storehouse_management') }}</span>
            </label>
        </div>
        <template v-if="product.with_storehouse_management">
            <div class="mb-3 position-relative">
                <label class="form-label">{{ __('order.quantity') }}</label>
                <input type="number" min="1" class="form-control" v-model="product.quantity" />
            </div>
            <div :class="{ 'position-relative': true, 'mb-3': store && store.id }">
                <label class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        v-model="product.allow_checkout_when_out_of_stock"
                        value="1"
                    />
                    <span class="form-check-label">{{ __('order.allow_customer_checkout_when_this_product_out_of_stock') }}</span>
                </label>
            </div>
        </template>
        <div class="position-relative" v-if="store && store.id">
            <label class="form-check-label">{{ __('order.store') }}: <strong class="text-primary">{{ store.name }}</strong></label>
        </div>
    </ec-modal>
</template>

<script>
export default {
    props: {
        store: {
            type: Object,
            default: () => ({}),
        },
    },
    data: function () {
        return {
            product: {},
        }
    },
    methods: {
        resetProductData: function () {
            this.product = {
                name: null,
                price: 0,
                sku: null,
                with_storehouse_management: false,
                allow_checkout_when_out_of_stock: false,
                quantity: 0,
                tax_price: 0,
            }
        },
    },
    mounted: function () {
        this.resetProductData()
    },
}
</script>
