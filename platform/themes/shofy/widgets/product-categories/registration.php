<?php

if (is_plugin_active('ecommerce')) {
    require_once __DIR__ . '/product-categories.php';

    register_widget(ProductCategoriesWidget::class);
}
