<script>
    $(function () {
        if (typeof gtag !== 'function') {
            return;
        }

        function formatItemCategories(categories) {
            if (!categories) {
                return {};
            }

            var formattedCategories = {};

            categories.split(',').forEach(function (key, index) {
                var keyName = index === 0 ? 'item_category': `item_category${index}`;
                formattedCategories[keyName] = key;
            });

            return formattedCategories;
        }

        $(document).on('click', '[data-bb-toggle="add-to-cart-in-form"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var form = currentTarget.closest('form');
            var price = currentTarget.data('product-price');
            var quantity = form.find('input[name="qty"]').val();
            var categories = formatItemCategories(currentTarget.data('product-categories'));

            gtag('event', 'add_to_cart', {
                currency: '{{ get_application_currency()->title }}',
                value: price * quantity,
                items: [
                    {
                        item_id: currentTarget.data('product-id'),
                        item_name: currentTarget.data('product-name'),
                        price: price,
                        quantity: quantity,
                        item_brand: currentTarget.data('product-brand'),
                        ...categories,
                    },
                ],
            });
        });
        $(document).on('click', '[data-bb-toggle="add-to-cart"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var price = currentTarget.data('product-price');
            var categories = formatItemCategories(currentTarget.data('product-categories'));

            gtag('event', 'add_to_cart', {
                currency: '{{ get_application_currency()->title }}',
                value: price,
                items: [
                    {
                        item_id: currentTarget.data('product-id'),
                        item_name: currentTarget.data('product-name'),
                        price: price,
                        quantity: 1,
                        item_brand: currentTarget.data('product-brand'),
                        ...categories,
                    },
                ],
            });
        });
        $(document).on('click', '[data-bb-toggle="remove-from-cart"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var price = currentTarget.data('product-price');
            var quantity = currentTarget.data('product-quantity');
            var categories = formatItemCategories(currentTarget.data('product-categories'));

            gtag('event', 'remove_from_cart', {
                currency: '{{ get_application_currency()->title }}',
                value: price * quantity,
                items: [
                    {
                        item_id: currentTarget.data('product-id'),
                        item_name: currentTarget.data('product-name'),
                        price: price,
                        quantity: quantity,
                        item_brand: currentTarget.data('product-brand'),
                        ...categories,
                    },
                ],
            });
        });
    });
</script>
