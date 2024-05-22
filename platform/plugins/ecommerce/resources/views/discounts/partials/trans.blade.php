<script>
    'use strict';

    window.trans = window.trans || {};

    window.trans.discount = {
        select_type_of_discount: '{{ trans('plugins/ecommerce::discount.select_type_of_discount') }}',
        coupon_code: '{{ trans('plugins/ecommerce::discount.coupon_code') }}',
        promotion: '{{ trans('plugins/ecommerce::discount.promotion') }}',
        create_discount_promotion: '{{ trans('plugins/ecommerce::discount.create_discount_promotion') }}',
        create_coupon_code: '{{ trans('plugins/ecommerce::discount.create_coupon_code') }}',
        generate_coupon_code: '{{ trans('plugins/ecommerce::discount.generate_coupon_code') }}',
        enter_promotion_name: '{{ trans('plugins/ecommerce::discount.enter_promotion_name') }}',
        customers_will_enter_this_coupon_code_when_they_checkout: '{{ trans('plugins/ecommerce::discount.customers_will_enter_this_coupon_code_when_they_checkout') }}',
        can_be_used_with_promotion: '{{ trans('plugins/ecommerce::discount.can_be_used_with_promotion') }}',
        unlimited_coupon: '{{ trans('plugins/ecommerce::discount.unlimited_coupon') }}',
        enter_number: '{{ trans('plugins/ecommerce::discount.enter_number') }}',
        apply_via_url: '{{ trans('plugins/ecommerce::discount.apply_via_url') }}',
        apply_via_url_description: '{{ trans('plugins/ecommerce::discount.apply_via_url_description') }}',
        display_at_checkout: '{{ trans('plugins/ecommerce::discount.display_at_checkout') }}',
        display_at_checkout_description: '{{ trans('plugins/ecommerce::discount.display_at_checkout_description') }}',
        description: '{{ trans('plugins/ecommerce::discount.description') }}',
        description_placeholder: '{{ trans('plugins/ecommerce::discount.description_placeholder') }}',
        coupon_type: '{{ trans('plugins/ecommerce::discount.coupon_type') }}',
        percentage_discount: '{{ trans('plugins/ecommerce::discount.percentage_discount') }}',
        free_shipping: '{{ trans('plugins/ecommerce::discount.free_shipping') }}',
        same_price: '{{ trans('plugins/ecommerce::discount.same_price') }}',
        apply_for: '{{ trans('plugins/ecommerce::discount.apply_for') }}',
        all_orders: '{{ trans('plugins/ecommerce::discount.all_orders') }}',
        order_amount_from: '{{ trans('plugins/ecommerce::discount.order_amount_from') }}',
        product_collection: '{{ trans('plugins/ecommerce::discount.product_collection') }}',
        product_category: '{{ trans('plugins/ecommerce::discount.product_category') }}',
        product: '{{ trans('plugins/ecommerce::discount.product') }}',
        customer: '{{ trans('plugins/ecommerce::discount.customer') }}',
        variant: '{{ trans('plugins/ecommerce::discount.variant') }}',
        once_per_customer: '{{ trans('plugins/ecommerce::discount.once_per_customer') }}',
        search_product: '{{ trans('plugins/ecommerce::discount.search_product') }}',
        no_products_found: '{{ trans('plugins/ecommerce::discount.no_products_found') }}',
        search_customer: '{{ trans('plugins/ecommerce::discount.search_customer') }}',
        no_customer_found: '{{ trans('plugins/ecommerce::discount.no_customer_found') }}',
        one_time_per_order: '{{ trans('plugins/ecommerce::discount.one_time_per_order') }}',
        one_time_per_product_in_cart: '{{ trans('plugins/ecommerce::discount.one_time_per_product_in_cart') }}',
        number_of_products: '{{ trans('plugins/ecommerce::discount.number_of_products') }}',
        selected_products: '{{ trans('plugins/ecommerce::discount.selected_products') }}',
        selected_customers: '{{ trans('plugins/ecommerce::discount.selected_customers') }}',
        time: '{{ trans('plugins/ecommerce::discount.time') }}',
        start_date: '{{ trans('plugins/ecommerce::discount.start_date') }}',
        end_date: '{{ trans('plugins/ecommerce::discount.end_date') }}',
        never_expired: '{{ trans('plugins/ecommerce::discount.never_expired') }}',
        save: '{{ trans('plugins/ecommerce::discount.save') }}',
        discount: '{{ trans('plugins/ecommerce::discount.discount') }}',
        when_shipping_fee_less_than: '{{ trans('plugins/ecommerce::discount.when_shipping_fee_less_than') }}',
        is: '{{ trans('plugins/ecommerce::discount.is') }}',
    }

    $(document).ready(function() {
        $(document).on('click', 'body', function(e) {
            let container = $('.box-search-advance');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.find('.panel').addClass('hidden');
            }
        });
    });
</script>
